<?php

namespace Ojstr\WorkflowBundle\Tests\Validation;

use Ojstr\WorkflowBundle\Tests\TestCase;
use Ojstr\WorkflowBundle\Validation\ViolationList;
use Ojstr\WorkflowBundle\Validation\Violation;

class ViolationListTest extends TestCase {

    public function testAdd() {
        $violationList = new ViolationList();
        $violationList->add(new Violation('Violation test'));
        $this->assertEquals(1, count($violationList));
    }

    public function testToString() {
        $violationList = new ViolationList();
        $violationList->add(new Violation('Violation test n°1'));
        $violationList->add(new Violation('Violation test n°2'));

        $expectedResult = <<<EOF
Violation test n°1
Violation test n°2

EOF;

        $this->assertEquals($expectedResult, $violationList->__toString());
    }

    public function testArrayAccess() {
        $violationList = new ViolationList();
        $violation = new Violation('Violation test');

        $violationList->add($violation);
        $this->assertEquals(1, count($violationList));

        $this->assertSame($violation, $violationList[0]);

        $violationList[1] = $violation;
        $this->assertSame($violation, $violationList[1]);
        $this->assertEquals(2, count($violationList));

        unset($violationList[1]);
        $this->assertFalse(isset($violationList[1]));

        try {
            $test = $violationList[1];
            $this->fail('An expected OutOfBoundsException has not been raised.');
        } catch (\OutOfBoundsException $e) {
            
        }

        try {
            $violationList[1] = 'Wrong argument';
            $this->fail('An expected InvalidArgumentException has not been raised.');
        } catch (\InvalidArgumentException $e) {
            
        }

        foreach ($violationList as $key => $violation) {
            $this->assertSame($violation, $violationList[$key]);
        }
    }

}
