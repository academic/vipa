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
class LoadReviewFormItemsData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{

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

        $form = $this->getReference('form1');

        $item1 = new ReviewFormItem();
        $item1->setFormId($form->getId());
        $item1->setFields(null);
        $item1->setTitle('The language of the manuscript');
        $item1->setInputType('radiobutton');
        $item1->setMandotary(true);
        $item1->setConfidential(true);

        $item2 = new ReviewFormItem();
        $item2->setFormId($form->getId());
        $item2->setFields(null);
        $item2->setTitle('Overall Reviewer Manuscript Rating');
        $item2->setInputType('scale1_5');
        $item2->setMandotary(true);
        $item2->setConfidential(true);

        $item3 = new ReviewFormItem();
        $item3->setFormId($form->getId());
        $item3->setFields(array('Correct', 'Needs revisions'));
        $item3->setTitle('The language of the manuscript');
        $item3->setInputType('radiobutton');
        $item3->setMandotary(true);
        $item3->setConfidential(true);

        $item4 = new ReviewFormItem();
        $item4->setFormId($form->getId());
        $item4->setFields(null);
        $item4->setTitle('Reviewer Confidential Comments to Editor');
        $item4->setInputType('textarea');
        $item4->setMandotary(false);
        $item4->setConfidential(true);

        $item5 = new ReviewFormItem();
        $item5->setFormId($form->getId());
        $item5->setFields(null);
        $item5->setTitle('Notes to Author');
        $item5->setInputType('textarea');
        $item5->setMandotary(false);
        $item5->setConfidential(false);

        $dm->persist($item1);
        $dm->persist($item2);
        $dm->persist($item3);
        $dm->persist($item4);
        $dm->persist($item5);
        $dm->flush();
    }

    public function getOrder()
    {
        return 22;
    }

}
