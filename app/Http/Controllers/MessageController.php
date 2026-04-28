<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AppwriteService;
use Appwrite\ID;

class MessageController extends Controller
{

    public function send(Request $request)
    {

        $appwrite = new AppwriteService();

        $appwrite->databases->createDocument(
            $appwrite->databaseId(),
            'messages',
            ID::unique(),
            [
                "complaint_id"=>$request->complaint_id,
                "sender_role"=>"admin",
                "sender_id"=>session('admin_id'),
                "message"=>$request->message,
                "created_at"=>now()->toISOString()
            ]
        );

        return back();
    }

    public function getByComplaint($id)
{

    $appwrite = new AppwriteService();

    $messages = $appwrite->databases->listDocuments(
        $appwrite->databaseId(),
        'messages',
        [
            \Appwrite\Query::equal('complaint_id', [$id])
        ]
    );

    return response()->json($messages['documents']);

}

}