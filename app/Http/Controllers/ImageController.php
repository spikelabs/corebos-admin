<?php

namespace App\Http\Controllers;

use App\Client;
use App\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    //

    public function get_form() {
        $image = null;
        return view("image_form", compact('image'));
    }

    public function get(Request $request) {
        $request->validate([
            "filter" => "nullable|string"
        ]);

        if ($request->input('filter')){
            $filter = $request->input('filter');
            $images = Image::where('name', 'like', "%$filter%")
                ->orWhere('dockerhub_image', 'like', "%$filter%")
                ->paginate();
        } else {
            $images = Image::paginate();

        }
        return view("images", compact('images'));
    }

    public function get_by_id($id) {
        $image = Image::find($id);

        if (!$image){
            return redirect(route("images"));
        }

        return view("image_form", compact('image'));
    }

    public function create(Request $request) {
        $request->validate([
            "name" => "required|string",
            "dockerhub_image" => "required|string",
            "sql_file" => "required|file"
        ]);

        $image_data = $request->only([
            "name",
            "dockerhub_image"
        ]);

        $extension = $request->file('sql_file')->getClientOriginalExtension();

        $file_name = str_random() . "." . $extension;

        $request->file('sql_file')
            ->storeAs('image_schemas', $file_name);

        $image_data["sql_file"] = $file_name;

        $image = Image::create($image_data);

        return redirect(route("image", ['id' => $image->id]));
    }

    public function update(Request $request, $id) {
        $request->validate([
            "name" => "required|string",
            "dockerhub_image" => "required|string",
            "sql_file" => "file|nullable"
        ]);

        $image_data = $request->only([
            "name",
            "dockerhub_image"
        ]);

        $image = Image::find($id);

        if (!$image) {
            return redirect(route("images"));
        }

        if (!empty($request->file('sql_file'))) {

            Storage::delete('image_schemas/' . $image->sql_file);

            $extension = $request->file('sql_file')->getClientOriginalExtension();

            $image_name = str_random() . "." . $extension;

            $request->file('sql_file')
                ->storeAs('image_schemas', $image_name);

            $image_data["sql_file"] = $image_name;
        }

        Image::where("id", $id)->update($image_data);

        return redirect(route("image", ['id' => $image->id]));
    }

    public function delete($id) {
        $image = Image::find($id);

        if(!$image){
            return redirect(route("images"));
        }

        $client_count = Client::where("image_id", $id)->count();

        if ($client_count > 0) {
            return redirect(route("images"))->withErrors([
                "Image is related to clients!"
            ]);
        }

        Storage::delete('image_schemas/' . $image->sql_file);

        Image::where("id", $id)->delete();

        return redirect(route("images"));
    }
}
