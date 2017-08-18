<?php

namespace Mubin\Events\BgEvents;

use \GuzzleHttp\Client as Guzzle;

/**
 * @author: Mubin
 */
class EventsPublisher
{
    protected $guzzle;

    /**
     * EventsPublisher constructor.
     * @param Guzzle $guzzle
     */
    function __construct(Guzzle $guzzle)
    {
        $this->guzzle = $guzzle;
        $this->guzzle->setDefaultOption('verify',false);

    }


    /**
     * Publish message to rethink db via node api.
     * @param $type string [global, user, backend]
     * @param $component    string  ["general", "criteria", "groups", "season", "profiles", "settings", "pricing_mode", "price_update"]
     * @param string $event_name string  event name to fire[only in case of backend]
     * @param array $data data that need to pass to rethink db.
     * @param bool $per_user
     * @return bool|String
     */
    public function publish($type, $component, $event_name = '', $data = [], $per_user = false)
    {
        $emit = [
            "component_name" => $component,
            "message_payload" => [
                'text' => $data['text']
            ]
        ];
        if ($type === "global") {
            $this->globalEvent($emit);
            return "Global Event Published";
        } elseif ($type === "user") {
            $emit['api_key'] = $data['api_key'];
            $emit['user_name'] = $data['user_name'];
            $emit['user_id'] = $data['user_id'];
            $emit['user_email'] = $data['user_email'];
            $emit['account_type'] = $data['account_type'];
            $this->localEvent($emit);
            return 'User Event Published';
        } elseif ($type == 'backend' && $per_user) {
            if (isset($data['api_key'])) {
                $emit['api_key'] = $data['api_key'];
                $emit['user_name'] = $data['user_name'];
                $emit['user_id'] = $data['user_id'];
                $emit['account_type'] = $data['account_type'];
                $emit['user_email'] = $data['user_email'];
                $emit['message_payload']['event_name'] = $event_name;
                $emit['message_payload']['event_meta'] = $data['event_meta'];
                $this->backendNotificationPerUser($emit);
            }
            return 'Backend Event Published';
        } elseif ($type == 'backend' && !$per_user) {
            $emit['message_payload']['event_name'] = $event_name;
            $emit['message_payload']['event_meta'] = $data['event_meta'];
            $this->backendGlobalNotification($emit);

            return 'Backend Event Published';
        }
        return false;
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
    private function localEvent($emit)
    {
        $this->guzzle->post(config('bgevents.push_user'), [
            'headers' => ['content-type' => 'application/json'],
            'body' => json_encode($emit)
        ]);

    }

    /**
     * @param array $emit
     * @return void
     */
    private function backendNotificationPerUser($emit)
    {
        $this->guzzle->post(config('bgevents.push_user'), [
            'headers' => ['content-type' => 'application/json'],
            'body' => json_encode($emit)
        ]);
    }

    /**
     * @param array $emit
     * @return void
     */
    private function backendGlobalNotification($emit)
    {
        $this->guzzle->post(config('bgevents.push_global'), [
            'headers' => ['content-type' => 'application/json'],
            'body' => json_encode($emit)
        ]);
    }
}