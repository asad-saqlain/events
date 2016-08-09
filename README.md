# events
Brokergenius Events package

add following line to your `config/app.php` under _providers_ list

    Mubin\Events\BrokergeniusEventsServiceProvider::class,
  
run
        
    composer dump-autoload

Then publish the configuration file using the `vendor:publish`

    php artisan vendor:publish

Update configurations in __config/bgevents.php__

run the `artisan` command to see feeds

    php artisan rethinkDb:consumeFeed --table=your_table