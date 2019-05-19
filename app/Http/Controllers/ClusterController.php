<?php

namespace App\Http\Controllers;

use App\Client;
use App\Cluster;
use Illuminate\Http\Request;

class ClusterController extends Controller
{
    //

    public function get_form() {
        $cluster = null;
        return view("cluster_form", compact('cluster'));
    }

    public function get(Request $request) {
        $request->validate([
            "filter" => "nullable|string"
        ]);

        if ($request->input('filter')){
            $filter = $request->input('filter');
            $clusters = Cluster::where('name', 'like', "%$filter%")
                ->orWhere('ip_address', 'like', "%$filter%")
                ->orWhere('cluster_id', 'like', "%$filter%")
                ->paginate();
        } else {
            $clusters = Cluster::paginate();

        }
        return view("clusters", compact('clusters'));
    }

    public function get_by_id($id) {
        $cluster = Cluster::find($id);

        if (!$cluster){
            return redirect(route("clusters"));
        }

        return view("cluster_form", compact('cluster'));
    }

    public function create(Request $request){
        $request->validate([
            "name" => "required|string",
            "ip_address" => "required|ip",
            "cluster_id" => "required|string"
        ]);


        $cluster_data = $request->only([
            "name",
            "ip_address",
            "cluster_id"
        ]);


        $cluster = Cluster::create($cluster_data);

        return redirect(route("cluster", ['id' => $cluster->id]));
    }

    public function update(Request $request, $id){
        $request->validate([
            "name" => "required|string",
            "ip_address" => "required|ip",
            "cluster_id" => "required|string"
        ]);


        $cluster_data = $request->only([
            "name",
            "ip_address",
            "cluster_id"
        ]);
        
        $cluster = Cluster::find($id);
        
        if (!$cluster) {
            return redirect(route("clusters"));
        }


        Cluster::where("id", $id)->update($cluster_data);

        return redirect(route("cluster", ['id' => $cluster->id]));
    }

    public function delete($id) {
        $cluster = Cluster::find($id);

        if(!$cluster){
            return redirect(route("clusters"));
        }

        $client_count = Client::where("cluster_id", $id)->count();

        if ($client_count > 0) {
            return redirect(route("clusters"))->withErrors([
                "Cluster contains clients resources!"
            ]);
        }

        Cluster::where("id", $id)->delete();

        return redirect(route("clusters"));
    }
}
