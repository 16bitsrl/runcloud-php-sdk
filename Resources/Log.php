<?php

namespace App\RuncloudApi\Resources;

class Log extends Resource
{

    public $id;
	public $type;
	public $severity;
	public $log;
	public $createdAtDate;
	public $createdAtTime;
	public $createdAtHumanize;
	public $idWebapp;
	public $idServer;

}