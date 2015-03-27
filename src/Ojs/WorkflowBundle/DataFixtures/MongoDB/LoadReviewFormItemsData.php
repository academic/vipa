<?php

namespace Ojs\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use \Ojs\WorkflowBundle\Document\ReviewFormItem;
use Doctrine\Common\DataFixtures\AbstractFixture;

/**
 * @todo
 * Create sample workflow
 */
class LoadReviewFormItemsData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface {

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $dm)
    {
        // content section
        $form = $this->getReference('form1');

        $item1 = new ReviewFormItem();
        $item1->setFormId($form->getId());
        $item1->setFields(null);
        $item1->setTitle('Title reflects paper content');
        $item1->setInputType('scale1_5');
        $item1->setMandatory(true);
        $item1->setConfidential(true);
        $item1->setFieldset('Content');

        $item2 = new ReviewFormItem();
        $item2->setFormId($form->getId());
        $item2->setFields(null);
        $item2->setTitle('Abstract reflects the papers content clearly');
        $item2->setInputType('scale1_5');
        $item2->setMandatory(true);
        $item2->setConfidential(true);
        $item2->setFieldset('Content');

        $item3 = new ReviewFormItem();
        $item3->setFormId($form->getId());
        $item3->setFields(null);
        $item3->setTitle('Keyword reflects the papers conten');
        $item3->setInputType('scale1_5');
        $item3->setMandatory(true);
        $item3->setConfidential(true);
        $item3->setFieldset('Content');

        $item4 = new ReviewFormItem();
        $item4->setFormId($form->getId());
        $item4->setFields(null);
        $item4->setTitle('Introduction presents the problem clearly');
        $item4->setInputType('scale1_5');
        $item4->setMandatory(true);
        $item4->setConfidential(true);
        $item4->setFieldset('Content');

        $item5 = new ReviewFormItem();
        $item5->setFormId($form->getId());
        $item5->setFields(null);
        $item5->setTitle('Citations are credible');
        $item5->setInputType('scale1_5');
        $item5->setMandatory(true);
        $item5->setConfidential(true);
        $item5->setFieldset('Content');

        // presentation section

        $item6 = new ReviewFormItem();
        $item6->setFormId($form->getId());
        $item6->setFields(array("Yes", "No"));
        $item6->setTitle('Units and terminology are used correctly');
        $item6->setInputType('radiobutton');
        $item6->setMandatory(true);
        $item6->setConfidential(true);
        $item6->setFieldset('Presentation');

        $item7 = new ReviewFormItem();
        $item7->setFormId($form->getId());
        $item7->setFields(array("Yes", "No"));
        $item7->setTitle('The English language is acceptable');
        $item7->setInputType('radiobutton');
        $item7->setMandatory(true);
        $item7->setConfidential(true);
        $item7->setFieldset('Presentation');

        $item8 = new ReviewFormItem();
        $item8->setFormId($form->getId());
        $item8->setFields(range(1, 10));
        $item8->setTitle('Quality of figures and tables is adequate');
        $item8->setInputType('dropdown');
        $item8->setMandatory(true);
        $item8->setConfidential(true);
        $item8->setFieldset('Presentation');

        // overall
        $item9 = new ReviewFormItem();
        $item9->setFormId($form->getId());
        $item9->setFields(array("Yes", "No"));
        $item9->setTitle('Will you be willing to review a revision of this manuscript? You reply would be helpful.');
        $item9->setInputType('radiobutton');
        $item9->setMandatory(true);
        $item9->setConfidential(true);
        $item9->setFieldset('Overall Scoring');

        $item10 = new ReviewFormItem();
        $item10->setFormId($form->getId());
        $item10->setFields(range(1, 10));
        $item10->setTitle('Review score');
        $item10->setInputType('radiobutton');
        $item10->setMandatory(true);
        $item10->setConfidential(true);
        $item10->setFieldset('Overall Scoring');

        $dm->persist($item1);
        $dm->persist($item2);
        $dm->persist($item3);
        $dm->persist($item4);
        $dm->persist($item5);
        $dm->persist($item6);
        $dm->persist($item7);
        $dm->persist($item8);
        $dm->persist($item9);
        $dm->persist($item10);

        $dm->flush();
    }

    public function getOrder()
    {
        return 21;
    }

}
