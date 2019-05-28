<?php

namespace App\Jobs;

use App\Client;
use App\Cluster;
use App\Database;
use App\DatabaseService;
use App\Image;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;

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

        $client = Client::find($this->client_id);
        $image = Image::find($client->image_id);

        $client_database = Database::where("client_id", $this->client_id)->first();

        $cluster = Cluster::find($client->cluster_id);

        $host = $cluster->ip_address;
        $username = $client_database->db_username;
        $password = $client_database->db_password;
        $db_database = $client_database->db_database;
        $db_port = $client_database->public_port;

        config([
            'database.connections.mysql.database' => $db_database,
            'database.connections.mysql.host' => $host,
            'database.connections.mysql.username' => $username,
            'database.connections.mysql.password' => $password,
            'database.connections.mysql.port' => $db_port
        ]);

        DB::purge('mysql');

        DB::reconnect('mysql');

        $query = file_get_contents(base_path("storage/image_schemas/" . $image->sql_file));

        DB::unprepared($query);

        config([
            'database.connections.mysql.database' => env("DB_DATABASE"),
            'database.connections.mysql.host' => env("DB_HOST"),
            'database.connections.mysql.username' => env("DB_USERNAME"),
            'database.connections.mysql.password' => env("DB_PASSWORD"),
            'database.connections.mysql.port' => env("DB_PORT"),
        ]);

        DB::purge('mysql');

        DB::reconnect('mysql');

        $job = (new CreateClientDeployment($this->client_id))
            ->onConnection('redis');

        Queue::push($job);
    }
}
