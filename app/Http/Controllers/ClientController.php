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
use App\DatabaseService;
use App\Deployment;
use App\DeploymentPvc;
use App\Ingress;
use App\Jobs\CreateClientDatabase;
use App\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{

    public function get(Request $request) {

        $request->validate([
            "filter" => "nullable|string"
        ]);

        if ($request->input('filter')){
            $filter = $request->input('filter');
            $clients = Client::where()
                ->orWhere('name', 'like', "%$filter%")
                ->orWhere('company_name', 'like', "%$filter%")
                ->orWhere('sub_domain', 'like', "%$filter%")
                ->paginate();
        } else {
            $clients = Client::paginate();

        }
        return view("clients", compact('clients'));
    }

    public function get_form() {
        $data = null;
        return view("client_form", compact('data'));
    }

    public function get_by_id($id) {
        $client = Client::find($id);

        if (!$client){
            return redirect(route("clients"));
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

        return view("client_form", compact('data'));
    }

    public function create(Request $request) {

        $request->validate([
            "name" => 'required|string',
            "email" => 'required|email',
            "company_name" => 'required|string',
            "description" => 'required|string',
            "sub_domain" => 'required|string'
        ]);

        $data = $request->only([
            "name",
            "email",
            "company_name",
            "description",
            "sub_domain",
        ]);

        $data = DB::transaction(function () use ($data){

            $client = Client::create($data);

            $label = strtolower($client->name . "-" . str_random(16));

            $deployment = Deployment::create([
                "client_id" => $client->id,
                "replicas" => 1,
                "name" => $label . "-deployment",
                "label" => $label,
            ]);

            $service = Service::create([
                "client_id" => $client->id,
                "deployment_id" => $deployment->id,
                "name" => $label . "-cluster-ip-service",
                "label" => $label
            ]);

            DeploymentPvc::create([
                "client_id" => $client->id,
                "deployment_id" => $deployment->id,
                "name" => $label . "-deployment-pvc",
                "storage" => "2Gi",
            ]);

            Ingress::create([
                "client_id" => $client->id,
                "service_id" => $service->id,
                "name" => $label . "-ingress",
                "sub_domain" => $client->sub_domain,
                "resource" => $service->name
            ]);

            $database = Database::create([
                "client_id" => $client->id,
                "name" => $label . "-database-deployment",
                "label" => $label . "-database",
                "db_username" => str_random(32),
                "db_password" => str_random(32),
                "db_database" => $client->name
            ]);

            $database_service = DatabaseService::create([
                "client_id" => $client->id,
                "database_id" => $database->id,
                "name" => $label . "-database-cluster-ip-service",
                "label" => $label . "-database"
            ]);

            $database_pvc = DatabasePvc::create([
                "client_id" => $client->id,
                "database_id" => $database->id,
                "storage" => "2Gi",
                "name" => $label . "-database-pvc"
            ]);

            return [
                'client' => $client,
                'database' => $database,
                'database_service' => $database_service,
                'database_pvc' => $database_pvc
            ];

        }, Controller::TRANSACTION_RETRY);

        $job = (new CreateClientDatabase(
                $data['database'],
                $data['database_service'],
                $data['database_pvc']
            ))
            ->onConnection('database');

        $this->dispatch($job);


        return redirect(route("client", ['id' => $data['client']->id]));

    }

    public function update(Request $request, $id) {
        $request->validate([
            "name" => 'required|string',
            'company_name' => 'required|string',
            'description' => 'required|string',
            'sub_domain' => 'required|string',
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
                'name' => $request->input('name'),
                'company_name' => $request->input('company_name'),
                'description' => $request->input('description'),
                'sub_domain' => $request->input('sub_domain')
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

            Ingress::where('client_id', $id)->update([
                'sub_domain' => $request->input('sub_domain')
            ]);

        }, Controller::TRANSACTION_RETRY);

        return redirect(route("client", ['id' => $id]));

    }

    public function delete($id) {
        $client = Client::find($id);

        if (!$client) {
            return redirect();
        }

        DB::transaction(function () use ($id) {

        });

        return redirect(route("clients"));
    }

}