
Sample usage

```php
$em = $this->getDoctrine()->getManager();
$currentUser = $this->container->get('security.token_storage')->getToken()->getUser();
$author = $this->getDoctrine()->getRepository('OjsUserBundle:User')->findOneBy(array('username' => 'demo_author'));
$txt = 'You can login as ' . $currentUser->getUsername();
// add notification
$n = new Notification();
$n->setRecipient($author);
$n->setSender($currentUser);
$n->setEntityId($currentUser->getId());
$n->setEntityName('User');
$n->setText($txt);
$n->setAction('attorney');
$n->setLevel(\Ojs\UserBundle\Entity\Param\NotificationParams::LEVEL_CONFIRMATION);
$em->persist($n);
$em->flush();
```
