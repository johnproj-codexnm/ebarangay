<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Appwrite\Query;
use Illuminate\Http\Request;
use App\Services\AppwriteService;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{

    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $appwrite = new AppwriteService();

        $users = $appwrite->databases->listDocuments(
            env('APPWRITE_DATABASE_ID'),
            'users',
            [
                Query::equal('email', [$request->email])
            ]
        );

        if(count($users['documents']) == 0){
            return back()->with('error','User not found');
        }

        $user = $users['documents'][0];

        if(!Hash::check($request->password, $user['password'])){
            return back()->with('error','Incorrect password');
        }

        if($user['role'] != 'admin'){
            return back()->with('error','Access denied. Not an admin.');
        }

        session([
            'admin_id' => $user['$id'],
            'admin_name' => $user['full_name']
        ]);

        return redirect('/admin/dashboard');
    }

    public function logout()
    {
        session()->forget('admin_id');
        return redirect('/admin/login');
    }
}