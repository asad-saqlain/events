<?php

/**
 * Created by PhpStorm.
 * User: Mubin
 * Date: 8/9/2016
 * Time: 3:50 PM
 */
use Mubin\Events\BgEvents\EventsPublisher;

class EventsPublisherTest extends TestCase
{
    protected $publisher;
    public function setUp()
    {
        parent::setUp();
        $this->publisher = new EventsPublisher(new \GuzzleHttp\Client);
    }

    public function testGlobalEventPublish(){


        $notification = [
            'text' => "Notification Pushed while testing."
        ];
        $response = $this->publisher->publish('global', 'price_update', '', $notification);

        $this->assertEquals($response, 'Global Event Published');
    }

    public function testUserNotification(){
        $notification = [
            'text' => 'Test Notification',
            'api_key' => '11895ad1dc5770fbe93bff8a0611fc5c',
            'user_name' => 'Unit Test',
            'user_id' => 01,
            'user_email' => 'test@gmail.com',
            'account_type' => 'sub_user'
        ];
        $response = $this->publisher->publish('user', 'general', '', $notification);
        $this->assertEquals($response, 'User Event Published');
    }
    public function testGlobalBackendEventPublish(){


        $notification = [
            'text' => "Sending from tests",
            'event_meta' => "test meta"
        ];
        $response = $this->publisher->publish('backend', 'backend', 'TestEvent', $notification);

        $this->assertEquals($response, 'Backend Event Published');
    }

    public function testUserBackendNotification(){
        $notification = [
            'text' => 'Test Notification',
            'api_key' => '11895ad1dc5770fbe93bff8a0611fc5c',
            'user_name' => 'Unit Test',
            'user_id' => 01,
            'user_email' => 'test@gmail.com',
            'account_type' => 'sub_user',
            'event_meta' => "test meta"
        ];
        $response = $this->publisher->publish('backend', 'backend', 'TestEvent', $notification);
        $this->assertEquals($response, 'Backend Event Published');
    }
}
