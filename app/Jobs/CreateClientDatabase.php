<?php

namespace App\Jobs;

use App\Database;
use App\DatabasePvc;
use App\Deployment;
use App\DeploymentPvc;
use App\Ingress;
use App\Service;
use App\DatabaseService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

require_once base_path("grpc/GPBMetadata/Kubernetes.php");
require_once base_path("grpc/ClientResponse.php");
require_once base_path("grpc/ClusterManagerClient.php");
require_once base_path("grpc/CreateClientDatabaseRequest.php");
require_once base_path("grpc/CreateClientDeploymentRequest.php");
require_once base_path("grpc/Database.php");
require_once base_path("grpc/DatabasePvc.php");
require_once base_path("grpc/DatabaseService.php");
require_once base_path("grpc/Deployment.php");
require_once base_path("grpc/DeploymentPvc.php");
require_once base_path("grpc/Ingress.php");
require_once base_path("grpc/Service.php");
require_once base_path("grpc/GrpcClient.php");


class CreateClientDatabase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $database;
    private $database_service;
    private $database_pvc;


    public function __construct(Database $database, DatabaseService $database_service, DatabasePvc $database_pvc)
    {
        $this->database = $database;
        $this->database_pvc = $database_pvc;
        $this->database_service = $database_service;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //

        $request = new \CreateClientDatabaseRequest();

        $database_data = new \Database();
        $database_data->setName($this->database->name);
        $database_data->setLabel($this->database->label);
        $database_data->setDbDatabase($this->database->db_database);
        $database_data->setDbUsername($this->database->db_username);
        $database_data->setDbPassword($this->database->db_password);
        $request->setDatabase($database_data);

        $database_service_data = new \DatabaseService();
        $database_service_data->setName($this->database_service->name);
        $database_service_data->setLabel($this->database_service->label);
        $request->setDatabaseService($database_service_data);

        $database_pvc_data = new \DatabasePvc();
        $database_pvc_data->setName($this->database_pvc->name);
        $database_pvc_data->setStorage($this->database_pvc->storage);
        $request->setDatabasePvc($database_pvc_data);

        $client = new \GrpcClient();

        list($response, $status) = $client->createClientDatabase($request);

        if ($status->code != 0) {
            throw new \Exception("grpc client connection error!");
        }

        if ($response->getSuccess() != 1) {
            throw new \Exception("invalid operation with grpc client");
        }

    }
}
