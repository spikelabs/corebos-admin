<?php

namespace App\Jobs;

use App\Database;
use App\DatabaseService;
use App\Deployment;
use App\DeploymentPvc;
use App\Ingress;
use App\Service;
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
require_once base_path("grpc/Deployment.php");
require_once base_path("grpc/DeploymentPvc.php");
require_once base_path("grpc/Ingress.php");
require_once base_path("grpc/Service.php");
require_once base_path("grpc/GrpcClient.php");


class CreateClientDeployment implements ShouldQueue
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


    public function handle()
    {
        //

        $request = new \CreateClientDeploymentRequest();

        $deployment = Deployment::where("client_id", $this->client_id)->first();
        $service = Service::where("client_id", $this->client_id)->first();
        $deployment_pvc = DeploymentPvc::where("client_id", $this->client_id)->first();
        $ingress = Ingress::where("client_id", $this->client_id)->first();
        $database = Database::where("client_id", $this->client_id)->first();
        $database_service = DatabaseService::where("client_id",  $this->client_id)->first();

        $deployment_data = new \Deployment();
        $deployment_data->setLabel($deployment->label);
        $deployment_data->setName($deployment->name);
        $deployment_data->setReplicas($deployment->replicas);
        $deployment_data->setDbHost($database_service->name);
        $deployment_data->setDbUsername($database->db_username);
        $deployment_data->setDbPassword($database->db_password);
        $deployment_data->setDbDatabase($database->db_database);
        $deployment_data->setSiteUrl("https://" . $ingress->sub_domain);
        $request->setDeployment($deployment_data);

        $service_data = new \Service();
        $service_data->setName($service->name);
        $service_data->setLabel($service->label);
        $request->setService($service_data);

        $deployment_pvc_data = new \DeploymentPvc();
        $deployment_pvc_data->setName($deployment_pvc->name);
        $deployment_pvc_data->setStorage($deployment_pvc->storage);
        $request->setDeploymentPvc($deployment_pvc_data);

        $ingress_data = new \Ingress();
        $ingress_data->setName($ingress->name);
        $ingress_data->setSubDomain($ingress->sub_domain);
        $ingress_data->setResource($ingress->resource);
        $request->setIngress($ingress_data);

        $client = new \GrpcClient();

        list($response, $status) = $client->createClientDeployment($request);

        if ($status->code != 0) {
            throw new \Exception("grpc client connection error!");
        }

        if ($response->getSuccess() != 1) {
            throw new \Exception($response->getError());
        }

    }
}
