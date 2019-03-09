<?php
/**
 * Created by PhpStorm.
 * User: geri
 * Date: 19-02-10
 * Time: 5.08.MD
 */

namespace App\Http\Controllers;


use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

    public function get() {

        $email = auth()->user()->email;
        return view('profile', compact('email'));
    }


    public function update(Request $request) {
        $request->validate([
            'email' => 'email',
            'password' => 'nullable|string|min:1'
        ]);


        $user = auth()->user();

        $data_to_update = [];
        if ($request->input('email')) {
            $data_to_update['email'] = $request->input('email');
        }

        if ($request->input('password')) {
            $data_to_update['password'] = Hash::make($request->input('password'));
        }

        if (!empty($data_to_update)) {
            User::where('id', $user->id)->update($data_to_update);
        }

        return redirect()->back();
    }

    public function logout() {
        auth()->logout();
        return redirect('/login');
    }

}