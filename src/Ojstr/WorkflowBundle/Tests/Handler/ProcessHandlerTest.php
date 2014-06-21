<?php

namespace Ojstr\WorkflowBundle\Tests\Handler;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Ojstr\WorkflowBundle\Flow\NextStateInterface;
use Ojstr\WorkflowBundle\Flow\Process;
use Ojstr\WorkflowBundle\Flow\Step;
use Ojstr\WorkflowBundle\Handler\ProcessHandler;
use Ojstr\WorkflowBundle\Model\ModelStorage;
use Ojstr\WorkflowBundle\Entity\ModelState;
use Ojstr\WorkflowBundle\Tests\TestCase;
use Ojstr\WorkflowBundle\Tests\Fixtures\FakeProcessListener;
Use Ojstr\WorkflowBundle\Tests\Fixtures\FakeModel;
use Ojstr\WorkflowBundle\Tests\Fixtures\FakeSecurityContext;
use Ojstr\WorkflowBundle\Tests\Fixtures\FakeValidatorListener;
use Ojstr\WorkflowBundle\Tests\Fixtures\FakeModelChecker;

class ProcessHandlerTest extends TestCase {

    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var Ojstr\WorkflowBundle\Model\ModelStorage
     */
    protected $modelStorage;

    protected function setUp() {
        parent::setUp();

        $this->em = $this->getMockSqliteEntityManager();
        $this->createSchema($this->em);

        $this->modelStorage = new ModelStorage($this->em, 'Ojstr\WorkflowBundle\Entity\ModelState');
    }

    public function testStart() {
        $model = new FakeModel();
        $modelState = $this->getProcessHandler()->start($model);

        $this->assertTrue($modelState instanceof ModelState);
        $this->assertEquals($model->getWorkflowIdentifier(), $modelState->getWorkflowIdentifier());
        $this->assertEquals('document_proccess', $modelState->getProcessName());
        $this->assertEquals('step_create_doc', $modelState->getStepName());
        $this->assertTrue($modelState->getCreatedAt() instanceof \DateTime);
        $this->assertTrue(is_array($modelState->getData()));
        $this->assertEquals(0, count($modelState->getData()));
        $this->assertEquals(FakeModel::STATUS_CREATE, $model->getStatus());
    }

    public function testStartBadCredentials() {
        $model = new FakeModel();
        $modelState = $this->getProcessHandler(false)->start($model);

        $this->assertTrue($modelState instanceof ModelState);
        $this->assertFalse($modelState->getSuccessful());
    }

    public function testStartWithData() {
        $data = array('some', 'informations');

        $model = new FakeModel();
        $model->data = $data;
        $modelState = $this->getProcessHandler()->start($model);

        $this->assertEquals($data, $modelState->getData());
    }

    /**
     * @expectedException        Ojstr\WorkflowBundle\Exception\WorkflowException
     * @expectedExceptionMessage The given model has already started the "document_proccess" process.
     */
    public function testStartAlreadyStarted() {
        $model = new FakeModel();
        $this->modelStorage->newModelStateSuccess($model, 'document_proccess', 'step_create_doc');

        $this->getProcessHandler()->start($model);
    }

    /**
     * @expectedException        Ojstr\WorkflowBundle\Exception\WorkflowException
     * @expectedExceptionMessage The given model has not started the "document_proccess" process.
     */
    public function testReachNextStateNotStarted() {
        $model = new FakeModel();

        $this->getProcessHandler()->reachNextState($model, 'validate');
    }

    public function testReachNextState() {
        $model = new FakeModel();
        $previous = $this->modelStorage->newModelStateSuccess($model, 'document_proccess', 'step_create_doc');

        $modelState = $this->getProcessHandler()->reachNextState($model, 'validate');

        $this->assertTrue($modelState instanceof ModelState);
        $this->assertEquals('step_validate_doc', $modelState->getStepName());
        $this->assertTrue($modelState->getSuccessful());
        $this->assertTrue($modelState->getPrevious() instanceof ModelState);
        $this->assertEquals($previous->getId(), $modelState->getPrevious()->getId());
        $this->assertEquals(FakeModel::STATUS_VALIDATE, $model->getStatus());
    }

    /**
     * @expectedException        Ojstr\WorkflowBundle\Exception\WorkflowException
     * @expectedExceptionMessage The step "step_create_doc" does not contain any next state named "step_fake".
     */
    public function testReachNextStateInvalidNextStep() {
        $model = new FakeModel();
        $this->modelStorage->newModelStateSuccess($model, 'document_proccess', 'step_create_doc');

        $modelState = $this->getProcessHandler()->reachNextState($model, 'step_fake');
    }

    public function testReachNextStateWithListener() {
        $this->assertEquals(0, FakeProcessListener::$call);

        $reflectionClass = new \ReflectionClass('Ojstr\WorkflowBundle\Handler\ProcessHandler');
        $method = $reflectionClass->getMethod('reachStep');
        $method->setAccessible(true);
        $method->invoke($this->getProcessHandler(), new FakeModel(), new Step('step_fake', 'Fake'));

        $this->assertEquals(1, FakeProcessListener::$call);
    }

    public function testReachNextStateOnInvalid() {
        $model = new FakeModel();
        $this->modelStorage->newModelStateSuccess($model, 'document_proccess', 'step_create_doc');

        $modelState = $this->getProcessHandler()->reachNextState($model, 'remove_on_invalid');

        $this->assertEquals('step_fake', $modelState->getStepName());
    }

    public function testReachNextStateOrValide() {
        // content is clean so we should go to validate
        $model = new FakeModel();
        $modelState = $this->getProcessHandler()->start($model);

        $this->assertTrue($modelState instanceof ModelState);
        $this->assertEquals('document_proccess', $modelState->getProcessName());
        $this->assertEquals('step_create_doc', $modelState->getStepName());

        $model->setContent('blablabla');
        $modelState = $this->getProcessHandler()->reachNextState($model, 'validate_or_remove');

        $this->assertTrue($modelState instanceof ModelState);
        $this->assertEquals('document_proccess', $modelState->getProcessName());
        $this->assertEquals('step_validate_doc', $modelState->getStepName());
    }

    public function testReachNextStateOrRemove() {
        // content is NOT clean so we should go to remove
        $model = new FakeModel();
        $modelState = $this->getProcessHandler()->start($model);

        $this->assertTrue($modelState instanceof ModelState);
        $this->assertEquals('document_proccess', $modelState->getProcessName());
        $this->assertEquals('step_create_doc', $modelState->getStepName());

        $model->setContent('');
        $modelState = $this->getProcessHandler()->reachNextState($model, 'validate_or_remove');

        $this->assertTrue($modelState instanceof ModelState);
        $this->assertEquals('document_proccess', $modelState->getProcessName());
        $this->assertEquals('step_remove_doc', $modelState->getStepName());
    }

    public function testExecuteValidations() {
        $model = new FakeModel();
        $this->modelStorage->newModelStateSuccess($model, 'document_proccess', 'step_create_doc');

        $modelState = $this->getProcessHandler()->reachNextState($model, 'remove');

        $this->assertFalse($modelState->getSuccessful());
        $this->assertEquals(array('Validation error!'), $modelState->getErrors());
    }

    /**
     * @expectedException        Ojstr\WorkflowBundle\Exception\WorkflowException
     * @expectedExceptionMessage Can't find step named "step_unknow" in process "document_proccess".
     */
    public function testGetProcessStepInvalidStepName() {
        $reflectionClass = new \ReflectionClass('Ojstr\WorkflowBundle\Handler\ProcessHandler');
        $method = $reflectionClass->getMethod('getProcessStep');
        $method->setAccessible(true);
        $method->invoke($this->getProcessHandler(), 'step_unknow');
    }

    public function testExecutePreValidations() {
        // reset fake calls
        FakeProcessListener::$call = 0;

        $model = new FakeModel();
        $this->modelStorage->newModelStateSuccess($model, 'document_proccess', 'step_create_doc');
        $modelState = $this->getProcessHandler()->reachNextState($model, 'validate_with_pre_validation');

        $this->assertTrue($modelState->getSuccessful());
        $this->assertEquals('step_validate_doc', $modelState->getStepName());

        $this->assertEquals(0, FakeProcessListener::$call);

        $model = new FakeModel();
        $this->modelStorage->newModelStateSuccess($model, 'document_proccess', 'step_create_doc');
        $modelState = $this->getProcessHandler()->reachNextState($model, 'validate_with_pre_validation_invalid');

        $this->assertFalse($modelState->getSuccessful());
        $this->assertEquals(array('Validation error!'), $modelState->getErrors());

        $this->assertEquals(1, FakeProcessListener::$call);
    }

    protected function getProcessHandler($authenticatedUser = true) {
        $stepValidateDoc = new Step(
                'step_validate_doc', 'Validate doc', array(), array('setStatus', 'Ojstr\WorkflowBundle\Tests\Fixtures\FakeModel::STATUS_VALIDATE')
        );

        $stepRemoveDoc = new Step(
                'step_remove_doc', 'Remove doc', array(), array('setStatus', 'Ojstr\WorkflowBundle\Tests\Fixtures\FakeModel::STATUS_REMOVE')
        );

        $stepRemoveOnInvalidDoc = new Step(
                'step_remove_on_invalid_doc', 'Remove doc', array(), array('setStatus', 'Ojstr\WorkflowBundle\Tests\Fixtures\FakeModel::STATUS_REMOVE'), array(), 'step_fake'
        );

        $stepFake = new Step('step_fake', 'Fake', array());

        $stepCreateDoc = new Step(
                'step_create_doc', 'Create doc', array(), array('setStatus', 'Ojstr\WorkflowBundle\Tests\Fixtures\FakeModel::STATUS_CREATE'), array('ROLE_ADMIN')
        );
        $stepCreateDoc->addNextState('validate', NextStateInterface::TYPE_STEP, $stepValidateDoc);
        $stepCreateDoc->addNextState('validate_with_pre_validation', NextStateInterface::TYPE_STEP, $stepValidateDoc);
        $stepCreateDoc->addNextState('validate_with_pre_validation_invalid', NextStateInterface::TYPE_STEP, $stepValidateDoc);
        $stepCreateDoc->addNextState('remove', NextStateInterface::TYPE_STEP, $stepRemoveDoc);
        $stepCreateDoc->addNextState('remove_on_invalid', NextStateInterface::TYPE_STEP, $stepRemoveOnInvalidDoc);
        $stepCreateDoc->addNextStateOr('validate_or_remove', NextStateInterface::TYPE_STEP_OR, array(
            array(
                'target' => $stepValidateDoc,
                'condition_object' => new FakeModelChecker(),
                'condition_method' => 'isClean',
            ),
            array(
                'target' => $stepRemoveDoc,
                'condition_object' => null,
                'condition_method' => null,
            ),
        ));

        $process = new Process(
                'document_proccess', array(
            'step_create_doc' => $stepCreateDoc,
            'step_validate_doc' => $stepValidateDoc,
            'step_remove_doc' => $stepRemoveDoc,
            'step_remove_on_invalid_doc' => $stepRemoveOnInvalidDoc,
            'step_fake' => $stepFake,
                ), 'step_create_doc', array('step_validate_doc')
        );

        $dispatcher = new EventDispatcher();
        $dispatcher->addListener('document_proccess.step_fake.reached', array(
            new FakeProcessListener(), 'handleSucccess'
        ));
        $dispatcher->addListener('document_proccess.step_remove_doc.validate', array(
            new FakeValidatorListener(), 'invalid'
        ));
        $dispatcher->addListener('document_proccess.step_remove_on_invalid_doc.validate', array(
            new FakeValidatorListener(), 'invalid'
        ));
        $dispatcher->addListener('document_proccess.step_validate_doc.validate', array(
            new FakeValidatorListener(), 'valid'
        ));
        $dispatcher->addListener('document_proccess.step_create_doc.validate_with_pre_validation.pre_validation', array(
            new FakeValidatorListener(), 'valid'
        ));
        $dispatcher->addListener('document_proccess.step_create_doc.validate_with_pre_validation_invalid.pre_validation', array(
            new FakeValidatorListener(), 'invalid'
        ));
        $dispatcher->addListener('document_proccess.step_create_doc.validate_with_pre_validation_invalid.pre_validation_fail', array(
            new FakeProcessListener(), 'handleSucccess'
        ));

        $processHandler = new ProcessHandler($process, $this->modelStorage, $dispatcher);
        $processHandler->setSecurityContext(new FakeSecurityContext($authenticatedUser));

        return $processHandler;
    }

}
