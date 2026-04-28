<?php

namespace App\Http\Controllers;

use App\Services\AppwriteService;
use Appwrite\Query;

class AdminDashboardController extends Controller
{

    public function index()
    {
        if(!session('admin_id')){
            return redirect('/admin/login');
        }

        $appwrite = new AppwriteService();

        // Total complaints
        $complaints = $appwrite->databases->listDocuments(
            $appwrite->databaseId(),
            'complaints'
        );

        // Total residents
        $residents = $appwrite->databases->listDocuments(
            $appwrite->databaseId(),
            'users',
            [
                Query::equal('role',['resident'])
            ]
        );

        // Pending complaints
        $pending = $appwrite->databases->listDocuments(
            $appwrite->databaseId(),
            'complaints',
            [
                Query::equal('status',['Pending'])
            ]
        );

        // Processing complaints
        $processing = $appwrite->databases->listDocuments(
            $appwrite->databaseId(),
            'complaints',
            [
                Query::equal('status',['Processing'])
            ]
        );

        // Resolved complaints
        $resolved = $appwrite->databases->listDocuments(
            $appwrite->databaseId(),
            'complaints',
            [
                Query::equal('status',['Resolved'])
            ]
        );

        return view('admin.dashboard',[
            'totalComplaints' => $complaints['total'],
            'totalResidents' => $residents['total'],
            'pending' => $pending['total'],
            'processing' => $processing['total'],
            'resolved' => $resolved['total'],
            'complaintList' => $complaints['documents']
        ]);
    }
}