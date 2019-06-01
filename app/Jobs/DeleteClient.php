<?php

namespace App\Jobs;

use App\Cluster;
use App\Http\Controllers\Controller;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DeleteClient implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $cluster_id;
    private $deployment_name;
    private $service_name;
    private $deployment_pvc_name;
    private $ingress_name;
    private $database_name;
    private $database_service_name;
    private $database_pvc_name;


    public function __construct($cluster_id, $deployment_name, $service_name, $deployment_pvc_name, $ingress_name, $database_name, $database_service_name, $database_pvc_name)
    {
        //
        $this->cluster_id = $cluster_id;
        $this->deployment_name = $deployment_name;
        $this->service_name = $service_name;
        $this->deployment_pvc_name = $deployment_pvc_name;
        $this->ingress_name = $ingress_name;
        $this->database_name = $database_name;
        $this->database_pvc_name = $database_pvc_name;
        $this->database_service_name = $database_service_name;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //

        $cluster = Cluster::find($this->cluster_id);

        $configData = Controller::get_cluster_config($cluster->cluster_id);

        $request = new \DeleteClientRequest();

        $request->setDeploymentName($this->deployment_name);
        $request->setServiceName($this->service_name);
        $request->setDeploymentPvcName($this->deployment_pvc_name);
        $request->setIngressName($this->ingress_name);
        $request->setDatabaseName($this->database_name);
        $request->setDatabasePvcName($this->database_pvc_name);
        $request->setDatabaseServiceName($this->database_service_name);
        $request->setConfigData($configData);

        $client = new \GrpcClient();

        list($response, $status) = $client->deleteClient($request);

        if ($status->code != 0) {
            throw new \Exception("grpc client connection error!");
        }

        if ($response->getSuccess() != 1) {
            throw new \Exception($response->getError());
        }
    }
}
