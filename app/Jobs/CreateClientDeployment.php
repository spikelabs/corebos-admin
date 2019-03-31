<?php

namespace App\Jobs;

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

    private $deployment;
    private $service;
    private $deployment_pvc;
    private $ingress;


    public function __construct(Deployment $deployment, Service $service, DeploymentPvc $deployment_pvc, Ingress $ingress)
    {
        //
        $this->deployment = $deployment;
        $this->service = $service;
        $this->deployment_pvc = $deployment_pvc;
        $this->ingress = $ingress;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //

        $request = new \CreateClientDeploymentRequest();

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

        $client = new \GrpcClient();

        list($response, $status) = $client->createClientDeployment($request);

        if ($status->code != 0) {
            throw new \Exception("grpc client connection error!");
        }

        if ($response->getSuccess() != 1) {
            throw new \Exception("invalid operation with grpc client");
        }

    }
}
