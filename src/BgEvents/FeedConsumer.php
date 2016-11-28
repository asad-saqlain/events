<?php

namespace Mubin\Events\BgEvents;

use r;
use Mubin\Events\BgEvents\Subscriber;

class FeedConsumer
{
    protected $subscriber;

    public function __construct(Subscriber $subscriber)
    {
        $this->subscriber = $subscriber;
    }

    /**
     * @param $table String  Table name to watch for feeds.
     */
    public function watch($table)
    {
        $connection = $this->openConnection();

        $feed = r\table($table)->changes()->run($connection);

        foreach ($feed as $change) {
            if ($change['new_val']['component'] == 'backend') {
                $event_name = $change['new_val']['message_payload']['event_name'];
                $event_meta = $change['new_val']['message_payload']['event_meta'];
                $this->subscriber->fire(['event_name' => $event_name, 'event_meta' => $event_meta]);
            }
        }
    }

    /**
     * @return r\Connection
     */
    public function openConnection()
    {
        $options = config('bgevents.rethink_db.options');
        $port = config('bgevents.rethink_db.port');
        $db = config('bgevents.rethink_db.db');
        $connection = r\connect($options, $port, $db);

        return $connection;
    }
}