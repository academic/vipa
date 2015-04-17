<?php

namespace Ojs\UserBundle\Tests\Controller;

use Ojs\Common\Tests\BaseTestCase;

class UserControllerTest extends BaseTestCase
{
    public function testUserList()
    {
        $this->logIn();
        $this->isAccessible(['user']);
        $result = $this->crawler->filter('html:contains("User List")')->count();
        $this->assertEquals(1, $result);
    }

    public function testUserShow()
    {
        $this->logIn();
        $this->isAccessible(['user_show',['id'=>1]]);
        $result = $this->crawler->filter('html:contains("Show User")')->count();
        $this->assertEquals(1, $result);
    }

    public function testUserNew()
    {
        $this->logIn();
        $this->isAccessible(['user_new']);
        $form = $this->crawler->selectButton('Create')->form();
        $form['ojs_userbundle_user[username]']='demo_new_user';
        $form['ojs_userbundle_user[password]']='password';
        $form['ojs_userbundle_user[email]']='demo_new_user@email.com';
        $form['ojs_userbundle_user[title]']='Doc';
        $form['ojs_userbundle_user[firstName]']='Aybars';
        $form['ojs_userbundle_user[lastName]']='Cengaver';
        $form['ojs_userbundle_user[status]']->select(1);
        $form['ojs_userbundle_user[roles]']=[1,2,3];
        $form['ojs_userbundle_user[subjects]']=[1,2,3];
        $form['ojs_userbundle_user[country]']->select(1);
        $form['ojs_userbundle_user[city]']->disableValidation();
        $form['ojs_userbundle_user[city]']->select(100);
        $form['ojs_userbundle_user[avatar]']='image.jpg';
        $form['ojs_userbundle_user[header]']='image.jpg';
        $result = $this->client->submit($form);
        $this->assertEquals(1,$result->filter('html:contains("Redirect")')->count());
        $result = $this->client->followRedirect();
        $this->assertEquals(1,$result->filter('html:contains("Show User")')->count());
    }

    public function testUserEdit()
    {
        $this->logIn();
        $this->isAccessible(['user_edit',['id'=>1]]);
        $form = $this->crawler->selectButton('Edit')->form();
        $form['ojs_userbundle_user[title]']='Doc';
        $form['ojs_userbundle_user[firstName]']='Aybars';
        $form['ojs_userbundle_user[lastName]']='Cengaver';
        $form['ojs_userbundle_user[password]']='admin';
        $result = $this->client->submit($form);
        $this->assertEquals(1,$result->filter('html:contains("Redirect")')->count());
        $result = $this->client->followRedirect();
        $this->assertEquals(1,$result->filter('html:contains("User Edit")')->count());
    }

    public function testUserDelete()
    {
        $this->logIn();
        $this->isAccessible(['user_delete',['id'=>50]]);
        $follow = $this->client->followRedirect();
        $this->assertEquals(1,$follow->filter('html:contains("User List")')->count());
    }

    public function testBlockUser()
    {
        $this->logIn();
        $this->isAccessible(['user_block',['id'=>40]]);
        $follow = $this->client->followRedirect();
        $this->assertEquals(1,$follow->filter('html:contains("User List")')->count());
    }

    public function testUnBlockUser()
    {
        $this->logIn();
        $this->isAccessible(['user_unblock',['id'=>40]]);
        $follow = $this->client->followRedirect();
        $this->assertEquals(1,$follow->filter('html:contains("User List")')->count());
    }

    public function testAnonymLoginCreate()
    {
        $this->logIn();
        $this->isAccessible(['user_create_anonym_login']);
        $form = $this->crawler->selectButton('Create')->form();
        $form['anonym_user[first_name]'] = 'Aybars';
        $form['anonym_user[last_name]'] = 'Cengaver';
        $form['anonym_user[email]'] = 'root@localhost.com';
        $form['anonym_user[roles]'] = [1,2,4];
        $this->client->submit($form);
        $result = $this->client->followRedirect();
        $this->assertEquals(1,$result->filter('html:contains("Create Anonym Login")')->count());
    }

    public function testAnonymLoginDelete()
    {
        $this->logIn();
        $em = $this->app->getKernel()->getContainer()->get('doctrine.orm.entity_manager');
        $user = $em->getRepository('OjsUserBundle:User')->findOneBy(['email'=>'root@localhost.com']);
        $dm = $this->app->getKernel()->getContainer()->get('doctrine.odm.mongodb.document_manager');
        $aut = $dm->getRepository('OjsUserBundle:AnonymUserToken')->findOneBy(['user_id'=>$user->getId()]);
        $this->isAccessible(['user_delete_anonym_login',['id'=>$aut->getId()]]);
        $result = $this->client->followRedirect();
        $this->assertEquals(1,$result->filter('html:contains("List Anonym Login")')->count());

    }
}
