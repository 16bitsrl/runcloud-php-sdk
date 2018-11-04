<?php

namespace App\RuncloudApi\Resources;

class Ssl extends Resource
{

	public $id;
	public $method;
	public $method_humanize;
	public $enableHTTP;
	public $enableHSTS;
	public $validUntil;
	public $renewal_date;
	public $runcloudAcme;
	public $authorizationMethod;
	public $certificate;
	public $privateKey;
    public $staging;
    public $idWebapp;
	public $idServer;
	
}