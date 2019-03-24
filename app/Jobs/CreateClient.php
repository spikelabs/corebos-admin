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
require_once base_path("grpc/ClusterManagerClient.php");
require_once base_path("grpc/CreateClientRequest.php");
require_once base_path("grpc/CreateClientResponse.php");
require_once base_path("grpc/Database.php");
require_once base_path("grpc/DatabasePvc.php");
require_once base_path("grpc/DatabaseService.php");
require_once base_path("grpc/Deployment.php");
require_once base_path("grpc/DeploymentPvc.php");
require_once base_path("grpc/Ingress.php");
require_once base_path("grpc/Service.php");


class CreateClient implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $deployment;
    private $service;
    private $deployment_pvc;
    private $ingress;
    private $database;
    private $database_service;
    private $database_pvc;


    public function __construct(Deployment $deployment, Service $service, DeploymentPvc $deployment_pvc, Ingress $ingress, Database $database, DatabaseService $database_service, DatabasePvc $database_pvc)
    {
        //
        $this->deployment = $deployment;
        $this->service = $service;
        $this->deployment_pvc = $deployment_pvc;
        $this->ingress = $ingress;
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

        $request = new \CreateClientRequest();

        $deployment_data = new \Deployment();
        $deployment_data->setLabel($this->deployment->label);
        $deployment_data->setName($this->deployment->name);
        $deployment_data->setReplicas($this->deployment->replicas);
        $request->setDeployment($deployment_data);

        $service_data = new \Service();
        $service_data->setName($this->service->name);
        $service_data->setLabel($this->service->label);
        $request->setService($service_data);

        $deployment_pvc_data = new \DeploymentPvc();
        $deployment_pvc_data->setName($this->deployment_pvc->name);
        $deployment_pvc_data->setStorage($this->deployment_pvc->storage);
        $request->setDeploymentPvc($deployment_pvc_data);

        $ingress_data = new \Ingress();
        $ingress_data->setName($this->ingress->name);
        $ingress_data->setSubDomain($this->ingress->sub_domain);
        $ingress_data->setResource($this->ingress->resource);
        $request->setIngress($ingress_data);

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

        list($response, $status) = $client->createClient($request);

        if ($status->code != 0) {
            throw new \Exception("grpc client connection error!");
        }

        if ($response->getSuccess() != 1) {
            throw new \Exception("invalid operation with grpc client");
        }

    }
}
