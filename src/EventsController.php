<?php

namespace Mubin\Events;

use Mubin\Events\BgEvents\EventsPublisher;
use App\Http\Controllers\Controller;

class EventsController extends Controller
{
    protected $publisher;

    public function __construct(EventsPublisher $publisher)
    {
        $this->publisher = $publisher;
    }

    public function globalEvent()
    {
        $notification = [
            'text' => "Prices are updated successfully for High Queue."
        ];

        $this->publisher->publish('global', 'price_update', '', $notification);
    }

    public function userEvent()
    {
        $notification = [
            'text' => 'Code Updated.',
            'api_key' => '11895ad1dc5770fbe93bff8a0611fc5c',
            'user_name' => 'Mubin',
            'user_id' => 30,
            'user_email' => 'mubin@brokergenius.com',
            'account_type' => 'sub_user'
        ];
        $this->publisher->publish('user', 'general', '', $notification);
    }

    public function updateCode()
    {
        $notification = [
            'text' => "Update Code.",
            'event_meta' => "bla bla bla"
        ];
        $this->publisher->publish('backend', 'backend', 'UpdateCode', $notification);
    }
}
