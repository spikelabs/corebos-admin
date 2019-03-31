<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateClientDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'MigrateDatabaseClient:migrateDatabaseClient';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if database is ready to accept connections, and migrate the initial schema';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
    }
}
