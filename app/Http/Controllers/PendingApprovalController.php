<?php
/**
 * Created by PhpStorm.
 * User: geri
 * Date: 19-07-20
 * Time: 2.21.MD
 */

namespace App\Http\Controllers;


use App\Cluster;
use App\Image;
use App\PendingApproval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PendingApprovalController extends Controller
{

    public function get(Request $request) {
        $request->validate([
            "filter" => "nullable|string"
        ]);

        if ($request->input('filter')){
            $filter = $request->input('filter');
            $pending_approvals = PendingApproval::where("email", "like", "%$filter%")
                                    ->orWhere("name", "like", "%$filter%")
                                    ->orWhere("company_name", "like", "%$filter%")
                                    ->paginate();
        } else {
            $pending_approvals = PendingApproval::paginate();

        }
        return view("pending_approvals", compact('pending_approvals'));
    }

    public function get_by_id($id) {
        $pending_approval = PendingApproval::find($id);

        if (!$pending_approval){
            return redirect(route("pending_approvals"));
        }

        $image = Image::find($pending_approval->image_id);
        $clusters = Cluster::all();

        $data = [
            "pending_approval" => $pending_approval,
            "image" => $image,
            "clusters" => $clusters,
        ];

        return view("pending_approval", compact('data'));
    }

    public function create(Request $request) {

        $validator = Validator::make($request->all(), [
            "name" => "required|string",
            "email" => "required|email",
            "company_name" => "required|string",
            "description" => "required|string",
            "image_id" => "required|integer"
        ]);

        if ($validator->fails()) {
            return [
                "success" => 0,
                "code" => 400,
                "data" => [
                    "message" => "Bad request!",
                    "errors" => $validator->errors()
                ]
            ];
        }

        $image = Image::find($request->input("image_id"));

        if (!$image) {
            return [
                "success" => 0,
                "code" => 404,
                "data" => [
                    "message" => "Image not found in server!"
                ]
            ];
        }

        $pending_approval_data = $request->only([
            "name",
            "email",
            "company_name",
            "description",
            "image_id"
        ]);

        PendingApproval::create($pending_approval_data);

        return [
            "success" => 1,
            "code" => 200,
            "data" => [
                "message" => "Successfully created pending approval!"
            ]
        ];
    }

    public function delete($id) {
        $pending_approval = PendingApproval::find($id);

        if(!$pending_approval){
            return redirect(route("pending_approvals"));
        }

        PendingApproval::where("id", $id)->delete();

        return redirect(route("pending_approvals"));
    }

}