<?php

namespace App\Jobs;

use App\Client;
use App\Cluster;
use App\Deployment;
use App\Http\Controllers\Controller;
use App\Image;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateClientDeployment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $client_id;
    private $image_tag;

    public function __construct($client_id, $image_tag)
    {
        $this->client_id = $client_id;
        $this->image_tag = $image_tag;
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

        $image = Image::find($client->image_id);

        $deployment = Deployment::where("client_id", $client->id)->first();

        $configData = Controller::get_cluster_config($cluster->cluster_id);

        $request = new \UpdateClientDeploymentRequest();

        $deployment_data = new \Deployment();
        $deployment_data->setName($deployment->name);
        $deployment_data->setImage($image->dockerhub_image . ":" . $this->image_tag);

        $request->setDeployment($deployment_data);
        $request->setConfigData($configData);

        $client = new \GrpcClient();

        list($response, $status) = $client->updateClientDeployment($request);

        if ($status->code != 0) {
            throw new \Exception("grpc client connection error!");
        }

        if ($response->getSuccess() != 1) {
            throw new \Exception($response->getError());
        }
    }
}
