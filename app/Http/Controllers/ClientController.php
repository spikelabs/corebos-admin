<?php
/**
 * Created by PhpStorm.
 * User: geri
 * Date: 19-02-23
 * Time: 8.36.MD
 */

namespace App\Http\Controllers;


use App\Client;
use App\Database;
use App\DatabasePvc;
use App\Deployment;
use App\DeploymentPvc;
use App\Ingress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{

    public function get(Request $request) {
        $clients = Client::paginate();

        return view("", compact('clients'));
    }

    public function get_by_id($id) {
        $client = Client::find($id);

        if (!$client){
            return redirect();
        }

        $data = [
            'client' => $client
        ];

        $data['deployment'] = Deployment::where("client_id", $id)->first();
        $data['service'] = Deployment::where("client_id", $id)->first();
        $data['deployment_pvc'] = DeploymentPvc::where("client_id", $id)->first();
        $data['ingress'] = Ingress::where("client_id", $id)->first();
        $data['database'] = Database::where("client_id", $id)->first();
        $data["database_pvc"] = DatabasePvc::where("client_id", $id)->first();

        return view("", compact('data'));
    }

    public function create(Request $request) {

        $request->validate([
            "name" => 'required|string',
            "email" => 'required|email',
            "username" => 'required|string',
            "password" => 'required|string',
            "company_name" => 'required|string',
            "description" => 'required|string',
            "sub_domain" => 'required|string'
        ]);

        $data = $request->only([
            "name",
            "email",
            "username",
            "password",
            "company_name",
            "description",
            "sub_domain",
        ]);

        $data["password"] = Hash::make($data["password"]);

        DB::transaction(function () use ($data){

            $client = Client::create($data);

            $label = str_random(16) . $client->name;

            $deployment = Deployment::create([
                "client_id" => $client->id,
                "replicas" => 1,
                "name" => $label . "-deployment",
                "label" => $label,
            ]);

            $service = Deployment::create([
                "client_id" => $client->id,
                "deployment_id" => $deployment->id,
                "name" => $label . "-cluster-ip-service",
                "label" => $label
            ]);

            $deployment_pvc = DeploymentPvc::create([
                "client_id" => $client->id,
                "deployment_id" => $deployment->id,
                "name" => $label . "-deployment-pvc",
                "storage" => "2Gi",
            ]);

            $ingress = Ingress::create([
                "client_id" => $client->id,
                "service_id" => $service->id,
                "name" => $label . "-ingress"
            ]);

            $database = Database::create([
                "client_id" => $client->id,
                "name" => $label . "-database-deployment",
                "label" => $label . "-database",
                "db_username" => str_random(32),
                "db_password" => str_random(32),
                "db_database" => $client->name
            ]);

            $database_pvc = DatabasePvc::create([
                "client_id" => $client->id,
                "database_id" => $database->id,
                "storage" => "2Gi",
                "name" => $label . "-database-pvc"
            ]);

        }, Controller::TRANSACTION_RETRY);

        return redirect();


    }

    public function update(Request $request, $id) {
        $request->validate([
            "name" => 'required|string',
            "replicas" => 'required|integer|min:1',
            "deployment_storage" => 'required|integer|min:1',
            'database_storage' => 'required|integer|min:1',
        ]);

        $client = Client::find($id);

        if (!$client) {
            return redirect();
        }

        DB::transaction(function () use ($request, $id){
            Client::where('id', $id)->update([
                'name' => $request->input('name')
            ]);

            Deployment::where("client_id", $id)->update([
                'replicas' => $request->input('replicas'),
            ]);

            DeploymentPvc::where('client_id', $id)->update([
                'storage' => $request->input('deployment_storage')
            ]);

            DatabasePvc::where('client_id', $id)->update([
                'storage' => $request->input("database_storage")
            ]);
        }, Controller::TRANSACTION_RETRY);

        return redirect();

    }

    public function delete($id) {
        $client = Client::find($id);

        if (!$client) {
            return redirect();
        }

        DB::transaction(function () use ($id) {

        });

        return redirect();
    }

}