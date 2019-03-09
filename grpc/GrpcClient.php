<?php
/**
 * Created by PhpStorm.
 * User: geri
 * Date: 19-03-02
 * Time: 8.45.MD
 */

require_once base_path("grpc/GPBMetadata/Kubernetes.php");
require_once base_path("grpc/ClusterManagerClient.php");
require_once base_path("grpc/CreateClientRequest.php");
require_once base_path("grpc/CreateClientResponse.php");
require_once base_path("grpc/Database.php");
require_once base_path("grpc/DatabasePvc.php");
require_once base_path("grpc/Deployment.php");
require_once base_path("grpc/DeploymentPvc.php");
require_once base_path("grpc/Ingress.php");
require_once base_path("grpc/Service.php");


class GrpcClient
{

    private $client;

    public function __construct()
    {
        $this->client = $client = new ClusterManagerClient(env("GRPC_CLIENT"), [
            'credentials' => Grpc\ChannelCredentials::createInsecure(),
        ]);
    }

    public function createClient(CreateClientRequest $request){
        return $this->client->CreateClient($request)->wait();
    }

}