<?php

namespace App\RuncloudApi\Resources;

class Server extends Resource
{


    /**
     * The id of the server.
     *
     * @var string
     */
    public $id;
    public $userId;
    public $serverId;
    public $serverName;
    public $ipAddress;
    public $serverProvider;
    public $connected;
    public $online;
    public $createdAt;
    public $createdAtHumanize;
    public $agentVersion;
    public $phpVersion;
    public $autoUpdate;

 
}
