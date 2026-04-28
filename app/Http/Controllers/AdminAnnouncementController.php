<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AppwriteService;
use Appwrite\ID;

class AdminAnnouncementController extends Controller
{

    public function index()
    {

        if(!session('admin_id')){
            return redirect('/admin/login');
        }

        $appwrite = new AppwriteService();

        $announcements = $appwrite->databases->listDocuments(
            $appwrite->databaseId(),
            'announcements'
        );

        return view('admin.announcements',[
            'announcements'=>$announcements['documents']
        ]);
    }



    public function store(Request $request)
    {

        $appwrite = new AppwriteService();

        $appwrite->databases->createDocument(
            $appwrite->databaseId(),
            'announcements',
            ID::unique(),
            [

                "title"=>$request->title,
                "content"=>$request->content,
                "created_by"=>session('admin_id'),
                "created_at"=>now()->toISOString()

            ]
        );

        return back()->with('success','Announcement posted successfully');

    }

    public function delete(Request $request)
{

    if(!session('admin_id')){
        return redirect('/admin/login');
    }

    $appwrite = new AppwriteService();

    $appwrite->databases->deleteDocument(
        $appwrite->databaseId(),
        'announcements',
        $request->id
    );

    return back()->with('success','Announcement deleted');

}

}