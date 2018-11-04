<?php

namespace App\RuncloudApi\Actions;

use App\RuncloudApi\Resources\Database;
use App\RuncloudApi\Resources\DatabaseUser;

trait ManagesDatabases
{
    /**
     * Get the collection of Databases.
     *
     * @return Database[]
     */
    public function databases(string $serverId)
    {
        $data = $this->getAllData("servers/$serverId/databases");
        return $this->transformCollection($data, Database::class);
    }

    /**
     * Get a Database instance.
     *
     * @param  string $serverId
     * @param  string $databaseId
     * @return Database
     */
    public function database(string $serverId, string $databaseId)
    {
        $data = $this->get("servers/$serverId/databases/$databaseId");
        return new Database($data, $this);
    }

    /**
     * Get the database users.
     *
     * @param  string $serverId
     * @return DatabaseUser[]
     */
    public function databaseUsers($serverId)
    {
        $extra = ['idServer' => $serverId];
        $data = $this->getAllData("servers/$serverId/databaseusers");

        return $this->transformCollection($data, DatabaseUser::class, $extra);
    }

     /**
     * Get a database user instance.
     *
     * @param  string $serverId
     * @param  string $dbuserId
     * @return DatabaseUser
     */
    public function databaseUser(string $serverId, string $dbuserId)
    {
        $extra = ['idServer' => $serverId];
        $data = $this->get("servers/$serverId/databaseusers/$dbuserId");

        return $this->transformItem($data, DatabaseUser::class, $extra);

    }

    // Create and delete database
    /**
     * Create a new database.
     *
     * @param  string $serverId
     * @param  string $name
     * @param  string $collation
     * @return string
     */
    public function createDatabase(string $serverId, string $name, string $collation='')
    {
        $data = [
            'databaseName' => $name,
            'databaseCollation' => $collation,
        ];

        return $this->post("servers/$serverId/databases", $data)['message'];
    }

    /**
     * Delete the given database.
     *
     * @param  string $serverId
     * @param  string $databaseId
     * @param  string $databaseName
     * @return string
     */
    public function deleteDatabase(string $serverId, string $databaseId, string $databaseName)
    {
        $data = [
            'databaseName' => $databaseName,
        ];
        
        return $this->delete("servers/$serverId/databases/$databaseId", $data)['message'];
    }

    // Create and delete database user and password change
    /**
     * Create a new database user.
     *
     * @param  string $serverId
     * @param  string $dbUserName
     * @param  string $password
     * @return string
     */
    public function createDbUser(string $serverId, string $dbUserName, string $password)
    {
        $data = [
            'databaseUser' => $dbUserName,
            'password' => $password,
            'verifyPassword' => $password,
        ];

        return $this->post("servers/$serverId/databaseusers", $data)['message'];
    }

    /**
     * Delete the given database user.
     *
     * @param  string $serverId
     * @param  string $dbUserId
     * @param  string $dbUserName
     * @return string
     */
    public function deleteDbUser(string $serverId, string $dbUserId, string $dbUserName)
    {
        $data = [
            'databaseUser' => $dbUserName
        ];
        
        return $this->delete("servers/$serverId/databaseusers/$dbUserId", $data)['message'];
    }

    /**
     * Change database user password.
     *
     * @param  string $serverId
     * @param  string $dbUserId
     * @param  string $password
     * @return string
     */
    public function changePasswordDbUser(string $serverId, string $dbUserId, string $password)
    {
         $data = [
            'password' => $password,
            'verifyPassword' => $password,
        ];
        
        return $this->patch("servers/$serverId/databaseusers/$dbUserId", $data)['message'];
    }

    // Attach and revoke database user
    /**
     * Attach database user to a database.
     *
     * @param  string $serverId
     * @param  string $databaseId
     * @param  string $dbUserName
     * @return string
     */
    public function attachDbUser(string $serverId, string $databaseId, string $dbUserName)
    {
        $data = [
            'databaseUser' => $dbUserName,
        ];

        return $this->post("servers/$serverId/databases/$databaseId/attachuser", $data)['message'];
    }

 /**
     * Revoke database user from database.
     *
     * @param  string $serverId
     * @param  string $databaseId
     * @param  string $dbUserName
     * @return string
     */
    public function revokeDbUser(string $serverId, string $databaseId, string $dbUserName)
    {
        $data = [
            'databaseUser' => $dbUserName,
        ];

        return $this->delete("servers/$serverId/databases/$databaseId/attachuser", $data)['message'];
    }
}
