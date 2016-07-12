<?php

namespace Tmcycyit\NotificationBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    const LOGIN = 'test';
    const PASS = 'test';

    /**
     *  This action is used to test homepage action
     */
    public function testIndex()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isRedirect(), "Homepage is not working!");

        // Check that the profiler is enabled
        if ($profile = $client->getProfile())
        {
            // check the number of requests
            $this->assertLessThan(10, $profile->getCollector('db')->getQueryCount());
        }
    }

    /**
     *  This action is used to test dashboard action
     */
    public function testDashboard()
    {
        $client = static::createClient();

        $client->request('GET', '/dashboard/');

        $crawler = $client->followRedirect(); // Redirect to login page

        $form = $crawler->selectButton('security.login.submit')->form(); //Select form, by button name
        $form['_username'] = self::LOGIN;   //enter username
        $form['_password'] = self::PASS;    //enter password
        $client->submit($form);             //submit data

        $client->followRedirect(); //redirect to dashboard

        $this->assertTrue($client->getResponse()->isSuccessful(), "Dashboard is not working!");

        // Check that the profiler is enabled
        if ($profile = $client->getProfile())
        {
            // check the number of requests
            $this->assertLessThan(10, $profile->getCollector('db')->getQueryCount());
        }
    }

    /**
     *  This action is used to test Send action
     */
    public function testSend()
    {
        $client = static::createClient();

        $client->request('GET', '/send/');

        $crawler = $client->followRedirect(); // Redirect to login page

        $form = $crawler->selectButton('security.login.submit')->form(); //Select form, by button name
        $form['_username'] = self::LOGIN;   //enter username
        $form['_password'] = self::PASS;    //enter password
        $client->submit($form);             //submit data

        $client->followRedirect(); //redirect to dashboard

        $this->assertTrue($client->getResponse()->isSuccessful(), "Send is not working!");

        // Check that the profiler is enabled
        if ($profile = $client->getProfile())
        {
            // check the number of requests
            $this->assertLessThan(10, $profile->getCollector('db')->getQueryCount());
        }
    }

    /**
     *  This action is used to test ShowSend action
     */
    public function testShowSend()
    {
        $client = static::createClient();

        $client->request('GET', '/show-send/');

        $crawler = $client->followRedirect(); // Redirect to login page

        $form = $crawler->selectButton('security.login.submit')->form(); //Select form, by button name
        $form['_username'] = self::LOGIN;   //enter username
        $form['_password'] = self::PASS;    //enter password
        $client->submit($form);             //submit data

        $client->followRedirect(); //redirect to dashboard

        $this->assertTrue($client->getResponse()->isSuccessful(), "ShowSend is not working!");

        // Check that the profiler is enabled
        if ($profile = $client->getProfile())
        {
            // check the number of requests
            $this->assertLessThan(10, $profile->getCollector('db')->getQueryCount());
        }
    }

    /**
     *  This action is used to test ShowReceive action
     */
    public function testShowReceive()
    {
        $client = static::createClient();

        $client->request('GET', '/show-receive/');

        $crawler = $client->followRedirect(); // Redirect to login page

        $form = $crawler->selectButton('security.login.submit')->form(); //Select form, by button name
        $form['_username'] = self::LOGIN;   //enter username
        $form['_password'] = self::PASS;    //enter password
        $client->submit($form);             //submit data

        $client->followRedirect(); //redirect to dashboard

        $this->assertTrue($client->getResponse()->isSuccessful(), "ShowReceive is not working!");

        // Check that the profiler is enabled
        if ($profile = $client->getProfile())
        {
            // check the number of requests
            $this->assertLessThan(10, $profile->getCollector('db')->getQueryCount());
        }
    }

    /**
     *  This action is used to test ReceiveDetailed action
     */
    public function testReceiveDetailed()
    {
        $client = static::createClient();

        $client->request('GET', '/receive-detailed/1'); //send random notification id

        $crawler = $client->followRedirect(); // Redirect to login page

        $form = $crawler->selectButton('security.login.submit')->form(); //Select form, by button name
        $form['_username'] = self::LOGIN;   //enter username
        $form['_password'] = self::PASS;    //enter password
        $client->submit($form);             //submit data

        $client->followRedirect(); //redirect to dashboard

        $this->assertTrue($client->getResponse()->isSuccessful(), "ReceiveDetailed is not working!");

        // Check that the profiler is enabled
        if ($profile = $client->getProfile())
        {
            // check the number of requests
            $this->assertLessThan(10, $profile->getCollector('db')->getQueryCount());
        }
    }

    /**
     *  This action is used to test SendDetailed action
     */
    public function testSendDetailed()
    {
        $client = static::createClient();

        $client->request('GET', '/send-detailed/1'); //send random notification id

        $crawler = $client->followRedirect(); // Redirect to login page

        $form = $crawler->selectButton('security.login.submit')->form(); //Select form, by button name
        $form['_username'] = self::LOGIN;   //enter username
        $form['_password'] = self::PASS;    //enter password
        $client->submit($form);             //submit data

        $client->followRedirect(); //redirect to dashboard

        $this->assertTrue($client->getResponse()->isSuccessful(), "SendDetailed is not working!");

        // Check that the profiler is enabled
        if ($profile = $client->getProfile())
        {
            // check the number of requests
            $this->assertLessThan(10, $profile->getCollector('db')->getQueryCount());
        }
    }
}