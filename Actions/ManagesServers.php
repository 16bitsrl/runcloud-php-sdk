<?php

namespace App\RuncloudApi\Actions;

use App\RuncloudApi\Resources\Server;
use App\RuncloudApi\Resources\ServerUser;
use App\RuncloudApi\Resources\Service;

trait ManagesServers
{

    // Objects and collections

    /**
     * Get the collection of servers.
     *
     * @return Server[]
     */
    public function servers()
    {
        $data = $this->getAllData('servers');
        return $this->transformCollection($data, Server::class);
    }

    /**
     * Get a server instance.
     *
     * @param  string $serverId
     * @return Server
     */
    public function server(string $serverId)
    {
        $data = $this->get("servers/$serverId");
        return new Server($data, $this);
    }

    /**
     * Get server summary.
     *
     * @param  string $serverId
     * @return array
     */
    public function serverSummary(string $serverId)
    {
        return $this->get("servers/$serverId/show");
    }

   /**
     * Get server hardware.
     *
     * @param  string $serverId
     * @return array
     */
    public function serverHardware(string $serverId)
    {
        return $this->get("servers/$serverId/show/data");
    }

    /**
     * Get the server users.
     *
     * @param  string $serverId
     * @return ServerUser[]
     */
    public function sysUsers(string $serverId)
    {
        $extra = ['idServer' => $serverId];
        $data = $this->getAllData("servers/$serverId/users");
        return $this->transformCollection($data, ServerUser::class, $extra);
    }

     /**
     * Get a system user instance.
     *
     * @param  string $serverId
     * @param  string $userId
     * @return ServerUser
     */
    public function sysUser(string $serverId, string $userId)
    {
        $extra = ['idServer' => $serverId];
        $data = $this->get("servers/$serverId/users/$userId");

        return $this->transformItem($data, ServerUser::class, $extra);
    }

    
    // Create and delete server
    /**
     * Create a new server.
     *
     * @param  array $data
     * @param  string $redirect (return redirect for activation)
     * @return Server
     */
    public function createServer(array $data, string &$redirect='')
    {
        $data = $this->post('servers', $data);

        $redirect = $data['redirect'];
        $server = new Server($data['server'], $this);

        return $server;

    }

    /**
     * Delete the given server.
     *
     * @param  string $serverId
     * @param  bool $onlineServer (force delete online server)
     * @return string
     */
    public function deleteServer(string $serverId, bool $onlineServer=false)
    {
        $data = [];
        if ($onlineServer) {
            $data = [
                'typeYes' => 'YES',
                'certifyToDeleteServer' => 'true',
                'proceedToDeletion' => 'true',
                'lastWarning' => 'true',
            ];        
        }

        return $this->delete("servers/$serverId", $data)['message'];
    }

    // Create and delete system user
    /**
     * Create a new system user
     *
     * @param  string $serverId
     * @param  string $sysUserName
     * @param  string $password
     * @return string
     */
    public function createSysUser(string $serverId, string $sysUserName, string $password)
    {
        $data = [
            'username' => $sysUserName,
            'password' => $password,
            'verifyPassword' => $password,
        ];

        return $this->post("servers/$serverId/users", $data)['message'];
    }

    /**
     * Delete the given system user.
     *
     * @param  string $serverId
     * @param  string $sysUserId
     * @param  string $sysUserName
     * @return string
     */
    public function deleteSysUser(string $serverId, string $sysUserId, string $sysUserName)
    {
        $data = [
            'username' => $sysUserName
        ];
        
        return $this->delete("servers/$serverId/users/$sysUserId", $data)['message'];
    }

    /**
     * Change system user password.
     *
     * @param  string $serverId
     * @param  string $sysUserId
     * @param  string $password
     * @return string
     */
    public function changePasswordSysUser(string $serverId, string $sysUserId, string $password)
    {
         $data = [
            'password' => $password,
            'verifyPassword' => $password,
        ];
        
        return $this->patch("servers/$serverId/users/$sysUserId", $data)['message'];
    }

    // Services
    /**
     * Get the services.
     *
     * @param  string $serverId
     * @return Service[]
     */
    public function services(string $serverId)
    {
        $extra = ['idServer' => $serverId];
        $data = $this->get("servers/$serverId/services")['data'];
        return $this->transformCollection($data, Service::class, $extra);
    }

     /**
     * Trigger systemctl commands: start, stop, restart, reload
     *
     * @param  string $serverId
     * @param  array $data
     * @return string
     */
    public function triggerService(string $serverId, array $data)
    {
        return $this->patch("servers/$serverId/services", $data)['message'];
    }

    /**
     * Trigger systemctl commands: start, stop, restart, reload
     *
     * @param  string $serverId
     * @param  string $realName
     * @param  string $name
     * @return string
     */
    public function startService(string $serverId, string $realName, string $name)
    {
        $data = [
            'action' => 'start',
            'realName' => $realName,
            'name' => $name,
        ];

        return $this->triggerService($serverId, $data);
    }
    
    public function stopService(string $serverId, string $realName, string $name)
    {
        $data = [
            'action' => 'stop',
            'realName' => $realName,
            'name' => $name,
        ];

        return $this->triggerService($serverId, $data);
    }
    
    public function restartService(string $serverId, string $realName, string $name)
    {
        $data = [
            'action' => 'restart',
            'realName' => $realName,
            'name' => $name,
        ];

        return $this->triggerService($serverId, $data);
    }

    public function reloadService(string $serverId, string $realName, string $name)
    {
        $data = [
            'action' => 'reload',
            'realName' => $realName,
            'name' => $name,
        ];

        return $this->triggerService($serverId, $data);
    }


}
