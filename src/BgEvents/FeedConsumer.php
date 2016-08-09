<?php
/**
 * Created by PhpStorm.
 * User: Mubin
 * Date: 8/9/2016
 * Time: 11:33 AM
 */

namespace Mubin\Events\BgEvents;

use r;

class FeedConsumer
{
    /**
     * @param $table String  Table name to watch for feeds.
     */
    public function watch($table){
        $connection = $this->openConnection();

        $feed = r\table($table)->changes()->run($connection);

        foreach($feed as $change){
            print_r($change);
        }
    }

    /**
     * @return r\Connection
     */
    public function openConnection(){
        $host = config('bgevents.rethink_db.host');
        $port = config('bgevents.rethink_db.port');
        $db = config('bgevents.rethink_db.db');
        $connection = r\connect($host, $port, $db);

        return $connection;
    }
}