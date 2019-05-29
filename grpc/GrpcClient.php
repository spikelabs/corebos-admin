<?php
/**
 * Created by PhpStorm.
 * User: geri
 * Date: 19-03-02
 * Time: 8.45.MD
 */

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


class GrpcClient
{

    private $client;

    public function __construct()
    {
        $this->client = $client = new ClusterManagerClient(env("GRPC_CLIENT"), [
            'credentials' => Grpc\ChannelCredentials::createInsecure(),
        ]);
    }

    public function createClientDatabase(CreateClientDatabaseRequest $request){
        return $this->client->CreateClientDatabase($request)->wait();
    }

    public function createClientDeployment(CreateClientDeploymentRequest $request){
        return $this->client->CreateClientDeployment($request)->wait();
    }

    public function updateClientIngress(UpdateClientIngressRequest $request) {
        return $this->client->UpdateClientIngress($request)->wait();
    }

    public function updateClientDeployment(UpdateClientDeploymentRequest $request) {
        return $this->client->UpdateClientDeployment($request)->wait();
    }

    public function deleteClient(DeleteClientRequest $request) {
        return $this->client->DeleteClient($request)->wait();
    }

}