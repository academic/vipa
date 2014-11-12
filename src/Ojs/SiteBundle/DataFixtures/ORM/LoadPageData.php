<?php
/**
 * User: aybarscengaver
 * Date: 12.11.14
 * Time: 22:59
 * URI: www.emre.xyz
 * Devs: [
 * 'Aybars Cengaver'=>'aybarscengaver@yahoo.com',
 *   ]
 */

namespace Ojs\SiteBundle\DataFixtures;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ojs\SiteBundle\Entity\Page;

class LoadPageData implements FixtureInterface
{
    public function load(ObjectManager $om)
    {
        $page = new Page();
        $page->setBody("<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.
 Autem maxime molestiae, perspiciatis praesentium qui ut voluptatem! Ab aliquid amet
 aspernatur aut beatae blanditiis consectetur cupiditate ducimus eum ex excepturi
 facilis fugiat harum inventore labore minus modi nam natus necessitatibus nostrum
 odio optio, pariatur perspiciatis placeat, praesentium quaerat, quisquam quo
 recusandae repellendus rerum sapiente sequi similique sunt veniam vitae voluptatem
  voluptatum! Architecto commodi cumque, deleniti, eum eveniet ex excepturi harum
   incidunt magni natus pariatur, placeat provident quas repudiandae sit unde vel vero.
    Ad adipisci autem dignissimos doloribus ducimus eum illum incidunt ipsa,
    libero magnam minima minus, nihil porro praesentium suscipit vitae!</p>");
        $page->setTags("demo,test");
        $page->setTitle("Demo content");
        $page->setImage("logo.png");
        $om->persist($page);
        $om->flush();
    }
} 