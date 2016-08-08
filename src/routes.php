<?php
/**
 * Created by PhpStorm.
 * User: Mubin
 * Date: 8/8/2016
 * Time: 10:19 AM
 */

Route::get('events/global', 'Mubin\Events\EventsController@globalEvent');
Route::get('events/user', 'Mubin\Events\EventsController@userEvent');