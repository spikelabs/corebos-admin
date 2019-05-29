<?php

namespace App\Jobs;

use App\Client;
use App\Cluster;
use App\Http\Controllers\Controller;
use App\Ingress;
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

class UpdateClientIngress implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $client_id;
    private $sub_domain;


    public function __construct($client_id, $sub_domain)
    {
        $this->client_id = $client_id;
        $this->sub_domain = $sub_domain;
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

        $ingress = Ingress::where("client_id", $client->id)->first();

        $configData = Controller::get_cluster_config($cluster->cluster_id);

        $request = new \UpdateClientIngressRequest();

        $ingress_data = new \Ingress();
        $ingress_data->setName($ingress->name);
        $ingress_data->setResource($ingress->resource);
        $ingress_data->setSubDomain($this->sub_domain);

        $request->setIngress($ingress_data);
        $request->setConfigData($configData);

        $client = new \GrpcClient();

        list($response, $status) = $client->updateClientIngress($request);

        if ($status->code != 0) {
            throw new \Exception("grpc client connection error!");
        }

        if ($response->getSuccess() != 1) {
            throw new \Exception($response->getError());
        }
    }
}
