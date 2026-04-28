<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AppwriteService;
use Appwrite\ID;
use Illuminate\Support\Facades\Hash;

class AdminRegisterController extends Controller
{

    public function showRegister()
    {
        return view('admin.register');
    }

    public function register(Request $request)
    {

        $appwrite = new AppwriteService();

        $appwrite->databases->createDocument(
            $appwrite->databaseId(),
            'users',
            ID::unique(),
            [
                "full_name" => $request->full_name,
                "email" => $request->email,
                "password" => Hash::make($request->password),
                "role" => "admin",
                "address" => "Barangay Office",
                "contact_number" => "N/A",
                "created_at" => now()->toISOString()
            ]
        );

        return redirect('/admin/login')->with('success','Admin account created');

    }
}