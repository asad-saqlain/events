<?php

Route::get('events/global', 'Mubin\Events\EventsController@globalEvent');
Route::get('events/user', 'Mubin\Events\EventsController@userEvent');
Route::get('events/backend_user', 'Mubin\Events\EventsController@userEvent');
Route::get('events/backend_global', 'Mubin\Events\EventsController@updateCode');