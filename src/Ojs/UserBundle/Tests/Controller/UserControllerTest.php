<?php

namespace Ojs\UserBundle\Tests\Controller;

use Ojs\Common\Tests\BaseTestCase;

class UserControllerTest extends BaseTestCase
{
    public function testUserList()
    {
        $this->logIn();
        $this->isAccessible(['ojs_admin_user_index']);
        $result = $this->crawler->filter('html:contains("User List")')->count();
        $this->assertEquals(1, $result);
    }

    public function testUserShow()
    {
        $this->logIn();
        $this->isAccessible(['ojs_admin_user_show', ['id' => 1]]);
        $result = $this->crawler->filter('html:contains("Show User")')->count();
        $this->assertEquals(1, $result);
    }

    public function testUserNew()
    {
        $this->logIn();
        $this->isAccessible(['ojs_admin_user_new']);
        $form = $this->crawler->selectButton('Create')->form();
        $form['ojs_userbundle_user[username]'] = 'demo_new_user';
        $form['ojs_userbundle_user[password]'] = 'password';
        $form['ojs_userbundle_user[email]'] = 'demo_new_user@email.com';
        $form['ojs_userbundle_user[title]'] = 'Doc';
        $form['ojs_userbundle_user[firstName]'] = 'Aybars';
        $form['ojs_userbundle_user[lastName]'] = 'Cengaver';
        $form['ojs_userbundle_user[isActive]']->tick();
        $form['ojs_userbundle_user[roles]'] = [1, 2, 3];
        $form['ojs_userbundle_user[subjects]'] = [1, 2, 3];
        $form['ojs_userbundle_user[country]']->select(1);
        $form['ojs_userbundle_user[city]']->disableValidation();
        $form['ojs_userbundle_user[city]']->select(100);
        $form['ojs_userbundle_user[avatar]'] = 'image.jpg';
        $form['ojs_userbundle_user[header]'] = 'image.jpg';
        $result = $this->client->submit($form);
        $this->assertEquals(1, $result->filter('html:contains("Redirect")')->count());
        $result = $this->client->followRedirect();
        $this->assertEquals(1, $result->filter('html:contains("Show User")')->count());
    }

    public function testUserEdit()
    {
        $this->logIn();
        $this->isAccessible(['ojs_admin_user_edit', ['id' => 1]]);
        $form = $this->crawler->selectButton('Edit')->form();
        $form['ojs_userbundle_user[title]'] = 'Doc';
        $form['ojs_userbundle_user[firstName]'] = 'Aybars';
        $form['ojs_userbundle_user[lastName]'] = 'Cengaver';
        $form['ojs_userbundle_user[password]'] = 'admin';
        $result = $this->client->submit($form);
        $this->assertEquals(1, $result->filter('html:contains("Redirect")')->count());
        $result = $this->client->followRedirect();
        $this->assertEquals(1, $result->filter('html:contains("User Edit")')->count());
    }

    public function testUserDelete()
    {
        $this->logIn();
        $this->isAccessible(['ojs_admin_user_delete', ['id' => 50]]);
        $follow = $this->client->followRedirect();
        $this->assertEquals(1, $follow->filter('html:contains("User List")')->count());
    }

    public function testBlockUser()
    {
        $this->logIn();
        $this->isAccessible(['ojs_admin_user_block', ['id' => 40]]);
        $follow = $this->client->followRedirect();
        $this->assertEquals(1, $follow->filter('html:contains("User List")')->count());
    }

    public function testUnBlockUser()
    {
        $this->logIn();
        $this->isAccessible(['ojs_admin_user_unblock', ['id' => 40]]);
        $follow = $this->client->followRedirect();
        $this->assertEquals(1, $follow->filter('html:contains("User List")')->count());
    }
}
