<?php

namespace Mubin\Events\Commands;

use Illuminate\Console\Command;
use Mubin\Events\BgEvents\FeedConsumer;

class ConsumeFeed extends Command
{
    protected $consumer;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rethinkDb:consumeFeed {--table=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to consume Feed from Rethink DB.';

    /**
     * Create a new command instance.
     * @param FeedConsumer $consumer
     */
    public function __construct(FeedConsumer $consumer)
    {
        parent::__construct();
        $this->consumer = $consumer;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $table = $this->option('table');
    }
}
