<?php

namespace App\RuncloudApi\Resources;

class Git extends Resource
{

    public $id;
   	public $provider;
    public $host;
    public $user;
    public $repository;
    public $repositoryURL;
    public $branch;
    public $webhookURL;
    public $autoDeploy;
    public $deployScript;
    public $created_at;
    public $created_at_humanize;
    public $webhookHistories;
    public $webhookErrors;
    public $idWebapp;
	public $idServer;

}