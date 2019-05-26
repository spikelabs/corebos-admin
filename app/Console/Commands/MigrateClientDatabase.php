<?php

namespace App\Console\Commands;

use App\Client;
use App\Database;
use App\DatabaseService;
use App\Jobs\MigrateClientSchema;
use Illuminate\Console\Command;
use mysqli;

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
    protected $description = 'Check if client database is ready to accept connections, and migrate the initial schema';

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

        $clients = Client::where("database_status", 0)->get();

        $client_ids = [];

        foreach ($clients as $client) {
            $id = $client->id;

            $client_database = Database::where("client_id", $id)->first();
            $database_service = DatabaseService::where("client_id", $id)->first();

            $host = $database_service->name;
            $username = $client_database->db_username;
            $password = $client_database->db_password;
            $db_database = $client_database->db_database;

            try{
                $conn = new mysqli($host, $username, $password, $db_database);

                if ($conn->connect_error) {
                    continue;
                }
            } catch (\Exception $e) {
                continue;
            }

            $job = (new MigrateClientSchema($id))
                ->onConnection('redis');

            dispatch($job);
            $client_ids[] = $id;
        }

        if (sizeof($client_ids) > 0){
            Client::whereIn("id", $client_ids)->update([
                "database_status" => 1
            ]);
        }
    }
}
