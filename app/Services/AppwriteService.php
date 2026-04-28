<?php

namespace App\Services;

use Appwrite\Client;
use Appwrite\Services\Databases;
use Appwrite\Services\Storage;

class AppwriteService
{
    protected $client;
    public $databases;
    public $storage;

    public function __construct()
    {
        $this->client = new Client();

        $this->client
            ->setEndpoint(env('APPWRITE_ENDPOINT'))
            ->setProject(env('APPWRITE_PROJECT_ID'))
            ->setKey(env('APPWRITE_API_KEY'));

        $this->databases = new Databases($this->client);
        $this->storage = new Storage($this->client);
    }

    public function databaseId()
    {
        return env('APPWRITE_DATABASE_ID');
    }

    public function bucketId()
    {
        return env('APPWRITE_BUCKET_ID');
    }
}