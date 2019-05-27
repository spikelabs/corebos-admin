<?php

namespace App\Jobs;

use App\Client;
use App\Cluster;
use App\Database;
use App\DatabasePvc;
use App\Deployment;
use App\DeploymentPvc;
use App\Http\Controllers\Controller;
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
require_once base_path("grpc/DatabaseNodePort.php");
require_once base_path("grpc/DatabasePvc.php");
require_once base_path("grpc/DatabaseService.php");
require_once base_path("grpc/DeleteClientRequest.php");
require_once base_path("grpc/Deployment.php");
require_once base_path("grpc/DeploymentPvc.php");
require_once base_path("grpc/Ingress.php");
require_once base_path("grpc/Service.php");
require_once base_path("grpc/UpdateClientDeploymentRequest.php");
require_once base_path("grpc/UpdateClientIngressRequest.php");
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
    private $client_id;


    public function __construct(Database $database, DatabaseService $database_service, DatabasePvc $database_pvc, $client_id)
    {
        $this->database = $database;
        $this->database_pvc = $database_pvc;
        $this->database_service = $database_service;
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

        $cluster = Cluster::find($client->cluster_id);

        $configData = Controller::get_cluster_config($cluster->id);

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

        $database_node_port = new \DatabaseNodePort();
        $database_node_port->setLabel($this->database->label);
        $database_node_port->setName($this->database->name . "-public");
        $database_node_port->setPort($this->database->public_port);
        $request->setDatabaseNodePort($database_node_port);
        $request->setConfigData($configData);

        $client = new \GrpcClient();

        list($response, $status) = $client->createClientDatabase($request);

        if ($status->code != 0) {
            throw new \Exception("grpc client connection error!");
        }

        if ($response->getSuccess() != 1) {
            throw new \Exception($response->getError());
        }

    }
}
