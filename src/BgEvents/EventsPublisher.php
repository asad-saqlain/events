<?php
/**
 * Created by PhpStorm.
 * User: Mubin
 * Date: 8/8/2016
 * Time: 10:51 AM
 */
namespace Mubin\Events\BgEvents;

use \GuzzleHttp\Client as Guzzle;

/**
 * @author: Mubin
 */
class EventsPublisher
{
    protected $global_channel;
    protected $user_channel;
    protected $guzzle;

    /**
     * EventsPublisher constructor.
     * @param Guzzle $guzzle
     */
    function __construct(Guzzle $guzzle)
    {
        $this->guzzle = $guzzle;

    }


    /**
     * Publish message to rethink db via node api.
     * @param $type string [global, user, backend]
     * @param $component    string  ["general", "criteria", "groups", "season", "profiles", "settings", "pricing_mode", "price_update"]
     * @param string $event_name    string  event name to fire[only in case of backend]
     * @param array $data   data that need to pass to rethink db.
     * @return void
     */
    public function publish($type, $component, $event_name = '', $data = []){
        $emit = [
            "component_name" => $component,
            "message_payload" => [
                'text' => $data['text']
            ]
        ];
        if($type === "global"){
            $this->globalEvent($emit);
        }
        elseif($type === "user"){
            $emit['api_key'] = $data['api_key'];
            $emit['user_name'] = $data['user_name'];
            $emit['user_id'] = $data['user_id'];
            $emit['user_email'] = $data['user_email'];
            $emit['account_type'] = $data['account_type'];
            $this->localEvent($emit);
        }
        elseif ($type == 'backend')
        {
            if(isset($data['api_key'])){
                $emit['api_key'] = $data['api_key'];
                $emit['user_name'] = $data['user_name'];
                $emit['user_id'] = $data['user_id'];
                $emit['account_type'] = $data['account_type'];
                $emit['user_email'] = $data['user_email'];
                $emit['event_name'] = $event_name;
                $this->backendNotification($emit);
            }
        }
    }

    /**
     * @param array $emit
     * @return void
     */
    private function globalEvent($emit)
    {
        $this->guzzle->post(config('bgevents.push_global'), [
            'headers' => ['content-type' => 'application/json'],
            'body' => json_encode($emit)
        ]);
    }

    /**
     * @param array $emit
     * @return void
     */
    private function localEvent($emit){
        $this->guzzle->post(config('bgevents.push_user'), [
            'headers' => ['content-type' => 'application/json'],
            'body' => json_encode($emit)
        ]);

    }

    /**
     * @param array $emit
     * @return void
     */
    private function backendNotification($emit){
        $this->guzzle->post(config('bgevents.push_notification'), [
            'headers' => ['content-type' => 'application/json'],
            'body' => json_encode($emit)
        ]);
    }
}