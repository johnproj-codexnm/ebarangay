<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Services\AppwriteService;

class AdminComplaintController extends Controller
{

    public function index()
    {

        if(!session('admin_id')){
            return redirect('/admin/login');
        }

        $appwrite = new AppwriteService();

        $complaints = $appwrite->databases->listDocuments(
            $appwrite->databaseId(),
            'complaints'
        );

        $evidence = $appwrite->databases->listDocuments(
            $appwrite->databaseId(),
            'complaints_evidence'
        );

        $messages = $appwrite->databases->listDocuments(
            $appwrite->databaseId(),
            'messages'
        );

        $finalComplaints = [];

    foreach($complaints['documents'] as $complaint){

        // Get user info
        $user = $appwrite->databases->listDocuments(
            $appwrite->databaseId(),
            'users',
            [
                \Appwrite\Query::equal('$id', [$complaint['user_id']])
            ]
        );

        if(count($user['documents']) > 0){

            $userData = $user['documents'][0];

            $complaint['resident_name'] = $userData['full_name'] ?? 'N/A';
            $complaint['resident_contact'] = $userData['contact_number'] ?? 'N/A';
            $complaint['resident_address'] = $userData['address'] ?? 'N/A';

        } else {

            $complaint['resident_name'] = 'Unknown';
            $complaint['resident_contact'] = 'N/A';
            $complaint['resident_address'] = 'N/A';

        }

        $finalComplaints[] = $complaint;
    }




        return view('admin.complaints',[
        'complaints' => $finalComplaints,
        'evidence' => $evidence['documents'],
        'messages' => $messages['documents']
    ]);
    }



    public function updateStatus(Request $request)
{

$appwrite = new AppwriteService();

$appwrite->databases->updateDocument(
$appwrite->databaseId(),
'complaints',
$request->complaint_id,
[
"status"=>$request->status
]
);

return back();

}

public function delete(Request $request)
{

    if(!session('admin_id')){
        return redirect('/admin/login');
    }

    $appwrite = new AppwriteService();

    $appwrite->databases->deleteDocument(
        $appwrite->databaseId(),
        'complaints',
        $request->complaint_id
    );

    return back()->with('success','Complaint marked as resolved & removed');

}

}