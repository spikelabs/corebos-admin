<?php
/**
 * Created by PhpStorm.
 * User: geri
 * Date: 19-02-23
 * Time: 8.36.MD
 */

namespace App\Http\Controllers;


use App\Client;
use App\Cluster;
use App\Database;
use App\DatabasePvc;
use App\DatabaseService;
use App\Deployment;
use App\DeploymentPvc;
use App\Image;
use App\Ingress;
use App\Jobs\CreateClientDatabase;
use App\Jobs\DeleteClient;
use App\Jobs\UpdateClientImage;
use App\Jobs\UpdateClientIngress;
use App\PendingApproval;
use App\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{

    public function get(Request $request) {

        $request->validate([
            "filter" => "nullable|string",
            "image_id" => "nullable|integer",
            "cluster_id" => "nullable|integer"
        ]);

        $filter = $request->input("filter");
        $image_id = $request->input("image_id", -1);
        $cluster_id = $request->input("cluster_id", -1);

        $clients = Client::where("cluster_id", $cluster_id);

        if ($cluster_id <= 0) {
            $clients = Client::where("cluster_id", '>', 0);
        }

        if ($image_id > 0) {
            $clients = $clients->where("image_id", $image_id);
        }

        if ($filter){
            $clients = $clients->where(function ($q) use ($filter){
                $q->where('name', 'like', "%$filter%")
                    ->orWhere('company_name', 'like', "%$filter%")
                    ->orWhere('sub_domain', 'like', "%$filter%");
            })->paginate();
        } else {
            $clients = $clients->paginate();
        }

        $clusters = Cluster::all();
        $images = Image::all();

        return view("clients", compact(
            'clients',
            'clusters',
            'images',
            'filter',
            'image_id',
            'cluster_id'));
    }

    public function get_form() {
        $data = [
            "clusters" => Cluster::all(),
            "images" => Image::all()
        ];

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

        $data['clusters'] = [Cluster::find($client->cluster_id)];
        $data['images'] = [Image::find($client->image_id)];

        return view("client_form", compact('data'));
    }

    public function create(Request $request) {

        $request->validate([
            "name" => 'required|string',
            "email" => 'required|email',
            "company_name" => 'required|string',
            "description" => 'required|string',
            "sub_domain" => 'required|string',
            "cluster_id" => 'required|integer',
            "image_id" => "required|integer"
        ]);

        $data = $request->only([
            "name",
            "email",
            "company_name",
            "description",
            "sub_domain",
            "cluster_id",
            "image_id",
        ]);

        $cluster = Cluster::find($data["cluster_id"]);

        if (!$cluster) {
            return back()->withErrors(["Cluster not found!"]);
        }

        $image = Image::find($data["image_id"]);

        if (!$image) {
            return back()->withErrors(["Image not found!"]);
        }

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
                "db_database" => $client->name,
                "cluster_id" => $data["cluster_id"],
                "public_port" => 30000 + rand(1, 2767)
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
                $data['database_pvc'],
                $data['client']->id
            ))
            ->onConnection('redis');

        $this->dispatch($job);


        return redirect(route("client", ['id' => $data['client']->id]));

    }

    public function update(Request $request, $id) {
        $request->validate([
            "name" => 'required|string',
            'company_name' => 'required|string',
            'description' => 'required|string',
            'sub_domain' => 'required|string',
            "email" => 'required|email'
        ]);

        $client = Client::find($id);

        if (!$client) {
            return redirect(route("clients"));
        }

        $result = DB::transaction(function () use ($request, $id, $client){

            Client::where('id', $id)->update([
                'name' => $request->input('name'),
                'company_name' => $request->input('company_name'),
                'description' => $request->input('description'),
                'sub_domain' => $request->input('sub_domain'),
                'email' => $request->input('email')
            ]);

            Ingress::where('client_id', $id)->update([
                'sub_domain' => $request->input('sub_domain')
            ]);

            return $request->input("sub_domain") == $client->sub_domain ? null : $request->input("sub_domain");

        }, Controller::TRANSACTION_RETRY);

        if ($result) {
            $job = (new UpdateClientIngress(
                $client->id,
                $result
            ))
                ->onConnection('redis');

            $this->dispatch($job);
        }

        return redirect(route("client", ['id' => $id]));

    }

    public function delete($id) {
        $client = Client::find($id);

        if (!$client) {
            return redirect(route("clients"));
        }

        $result = DB::transaction(function () use ($id) {
            $deployment = Deployment::where("client_id", $id)->first();
            $deployment_pvc = DeploymentPvc::where("client_id", $id)->first();
            $service = Service::where("client_id", $id)->first();
            $ingress = Ingress::where("client_id", $id)->first();
            $database = Database::where("client_id", $id)->first();
            $database_service = DatabaseService::where("client_id", $id)->first();
            $database_pvc = DatabasePvc::where("client_id", $id)->first();

            DatabasePvc::where("client_id", $id)->delete();
            DatabaseService::where("client_id", $id)->delete();
            Database::where("client_id", $id)->delete();
            Ingress::where("client_id", $id)->delete();
            DeploymentPvc::where("client_id", $id)->delete();
            Service::where("client_id", $id)->delete();
            Deployment::where("client_id", $id)->delete();
            Client::where("id", $id)->delete();

            return [
                "deployment_name" => $deployment->name,
                "deployment_pvc_name" => $deployment_pvc->name,
                "service_name" => $service->name,
                "ingress_name" => $ingress->name,
                "database_name" => $database->name,
                "database_service_name" => $database_service->name,
                "database_pvc_name" => $database_pvc->name
            ];
        });

        $job = (new DeleteClient(
            $client->cluster_id,
            $result["deployment_name"],
            $result["service_name"],
            $result["deployment_pvc_name"],
            $result["ingress_name"],
            $result["database_name"],
            $result["database_service_name"],
            $result["database_pvc_name"]
        ))
            ->onConnection('redis');

        $this->dispatch($job);

        return redirect(route("clients"));
    }

    public function updateClientImage(Request $request) {

        $validator = Validator::make($request->all(), [
            "token" => "required|string",
            "image_tag" => "required|string"
        ]);

        if ($validator->fails()) {
            return [
                "success" => 0
            ];
        }

        if ($request->input("token") != env("API_TOKEN")) {
            return [
                "success" => 0
            ];
        }

        $job = (new UpdateClientImage(
            $request->input("image_tag")
        ))
            ->onConnection('redis');

        $this->dispatch($job);

        return [
            "success" => 1
        ];
    }

    public function approve(Request $request) {
        $request->validate([
            "id" => "required|integer",
            "sub_domain" => 'required|string',
            "cluster_id" => 'required|integer',
        ]);

        $pending_approval = PendingApproval::find($request->input("id"));

        if (!$pending_approval) {
            return redirect(route("pending_approvals"));
        }

        $cluster = Cluster::find($request->input("cluster_id"));

        if (!$cluster) {
            return redirect(route("pending_approvals"));
        }

        $sub_domain = $request->input("sub_domain");

        $data = DB::transaction(function () use ($pending_approval, $cluster, $sub_domain){

            $client = Client::create([
                "name" => $pending_approval->name,
                "email" => $pending_approval->email,
                "company_name" => $pending_approval->company_name,
                "sub_domain" => $sub_domain,
                "cluster_id" => $cluster->id,
                "image_id" => $pending_approval->image_id,
                "description" => $pending_approval->description
            ]);

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
                "db_database" => $client->name,
                "cluster_id" => $cluster->id,
                "public_port" => 30000 + rand(1, 2767)
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
            $data['database_pvc'],
            $data['client']->id
        ))
            ->onConnection('redis');

        $this->dispatch($job);


        return redirect(route("client", ['id' => $data['client']->id]));

    }

}