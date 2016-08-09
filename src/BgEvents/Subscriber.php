<?php
/**
 * Created by PhpStorm.
 * User: Mubin
 * Date: 8/9/2016
 * Time: 5:44 PM
 */

namespace Mubin\Events\BgEvents;

use \Event;
use \Log;
use ReflectionException;

class Subscriber{
    public function fire($event_data){
        $event_name = $event_data['event_name'];
        try{
            $event_file = "App\\Events\\".$event_name;
            $event = \App::make($event_file);
            Event::fire(new $event($event_data['event_meta']));
        }catch(ReflectionException $e){
            echo "$event_name not found";
            Log::critical("$event_name not found");
        }
    }
}