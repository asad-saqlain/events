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
                $event_name = $change['new_val']['payload']['event_name'];
                $event_meta = $change['new_val']['payload']['event_meta'];
                $this->subscriber->fire(['event_name' => $event_name, 'event_meta' => $event_meta]);
            }
        }
    }

    /**
     * @return r\Connection
     */
    public function openConnection()
    {
        $host = config('bgevents.rethink_db.options.host');
        $port = config('bgevents.rethink_db.port');
        $db = config('bgevents.rethink_db.db');
        $user = config('bgevents.rethink_db.options.user');
        $password = config('bgevents.rethink_db.options.password');
        $path = base_path('build/cacert.pem');
        $connect_arr = [
            'host' => $host,
            'port' => $port,
            'db' => $db,
            'user' => $user,
            'password' => $password,
            'timeout' => 50,
            'ssl' => [
                'cafile' => $path,
                'peer_fingerprint' => openssl_x509_fingerprint(file_get_contents($path)),
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
                'verify_depth' => 0
            ]
        ];
        $connection = r\connect($connect_arr);
        return $connection;
    }
}