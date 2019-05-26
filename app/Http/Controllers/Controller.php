<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    const TRANSACTION_RETRY = 2;

    public static function get_cluster_config($cluster_id) {
        $client = new Client(['base_uri' => "https://api.digitalocean.com/"]);

        $response = $client->get("/v2/kubernetes/clusters/$cluster_id/kubeconfig", [
            "stream" => true,
            "headers" => [
                "Authorization"=> "Bearer " . env("DIGITAL_OCEAN_TOKEN")
            ]
        ]);

        if ($response->getStatusCode() != 200) {
            throw new \Exception("Cannot get cluster config!");
        }

        $body = $response->getBody();
        $data = "";
        while (!$body->eof()) {
            $data .= $body->read(1024);
        }

        return $data;
    }
}
