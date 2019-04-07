<?php

namespace App\Jobs;

use App\Database;
use App\DatabaseService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class MigrateClientSchema implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $client_id;

    public function __construct($client_id)
    {
        //

        $this->client_id = $client_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //

        $client_database = Database::where("client_id", $this->client_id)->first();
        $database_service = DatabaseService::where("client_id", $this->client_id)->first();


        $host = $database_service->name;
        $username = $client_database->db_username;
        $password = $client_database->db_password;
        $db_database = $client_database->db_database;

        config([
            'database.connections.mysql.database' => $db_database,
            'database.connections.mysql.host' => $host,
            'database.connections.mysql.username' => $username,
            'database.connections.mysql.password' => $password,
        ]);

        DB::purge();

        DB::reconnect();

        $query = file_get_contents(base_path("gdprcore.sql"));

        DB::unprepared($query);

        config([
            'database.connections.mysql.database' => env("DB_DATABASE"),
            'database.connections.mysql.host' => env("DB_HOST"),
            'database.connections.mysql.username' => env("DB_USERNAME"),
            'database.connections.mysql.password' => env("DB_PASSWORD"),
        ]);

        DB::purge();

        DB::reconnect();

        $job = (new CreateClientDeployment($this->client_id))
            ->onConnection('database');

        dispatch($job);
    }
}
