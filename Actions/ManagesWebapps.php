<?php

namespace App\RuncloudApi\Actions;

use App\RuncloudApi\Resources\Webapp;
use App\RuncloudApi\Resources\Domain;
use App\RuncloudApi\Resources\Log;
use App\RuncloudApi\Resources\Ssl;

trait ManagesWebapps
{
    /**
     * Get the collection of webapps on the server
     *
     * @param  string $serverId
     * @return Webapp[]
     */
    public function webapps(string $serverId)
    {
        $data = $this->getAllData("servers/$serverId/webapps");

        return $this->transformCollection($data, Webapp::class);
    }

    /**
     * Get a webapp instance.
     *
     * @param  string $serverId
     * @param  string $webappId
     * @return Webapp
     */
    public function webapp(string $serverId, string $webappId)
    {
        $data = $this->get("servers/$serverId/webapps/$webappId");

        return new Webapp($data, $this);
    }

   // Create and delete a webapp
    /**
     * Create a new webapp.
     *
     * @param  string $serverId
     * @param  array $data
     * @return string
     */
    public function createWebapp(string $serverId, array $data)
    {
        // $result = $this->post("servers/$serverId/webapps", $data);
        // if (is_object($result)) {
        //     return $result['message'];
        // } else {
        //     return 'Check data: webapp not created';
        // }

        return $this->post("servers/$serverId/webapps", $data)['message'];
    }

    /**
     * Delete the given webapp.
     *
     * @param  string $serverId
     * @param  string $webappId
     * @param  string $webappName
     * @return string
     */
    public function deleteWebapp(string $serverId, string $webappId, string $webappName)
    {
        $data = [
            'webApplicationName' => $webappName,
        ];
        
        return $this->delete("servers/$serverId/webapps/$webappId", $data)['message'];
    }

     /**
     * Set webapp as default.
     *
     * @param  string $serverId
     * @param  string $webappId
     * @return string
     */
    public function setWebappDefault(string $serverId, string $webappId)
    {
        return $this->post("servers/$serverId/webapps/$webappId/default")['message'];
    }

    /**
     * Remove webapp default.
     *
     * @param  string $serverId
     * @param  string $webappId
     * @return string
     */
    public function unsetWebappDefault(string $serverId, string $webappId)
    {
        return $this->delete("servers/$serverId/webapps/$webappId/default")['message'];
    }

   /**
     * Rebuild webapp.
     *
     * @param  string $serverId
     * @param  string $webappId
     * @return string
     */
    public function rebuildWebapp(string $serverId, string $webappId)
    {

        return $this->patch("servers/$serverId/webapps/$webappId/rebuild")['message'];
    }


    // Domains
     /**
     * Get the domains.
     *
     * @param  string $serverId
     * @param  string $webappId
     * @return Domain
     */
    public function domains(string $serverId, string $webappId)
    {
        $extra = ['idServer' => $serverId, 'idWebapp' => $webappId];
        $data = $this->get("servers/$serverId/webapps/$webappId/domainname")['data'];

        return $this->transformCollection($data, Domain::class, $extra);
    }

    /**
     * Add domain to a webapp.
     *
     * @param  string $serverId
     * @param  string $webappId
     * @return string
     */
    public function addDomain(string $serverId, string $webappId, string $domainName)
    {
        $data = [
            'domainName' => $domainName,
        ];

        return $this->post("servers/$serverId/webapps/$webappId/domainname", $data);
    }

    /**
     * Delete the domain from the webapp.
     *
     * @param  string $serverId
     * @param  string $webappId
     * @param  string $domainId
     * @return string
     */
    public function deleteDomain(string $serverId, string $webappId, string $domainId)
    {
        return $this->delete("servers/$serverId/webapps/$webappId/domainname/$domainId")['message'];
    }

    // GIT
    /**
     * Get Git.
     *
     * @param  string $serverId
     * @param  string $webappId
     * @return array
     */
    public function webappGit(string $serverId, string $webappId)
    {
        $extra = ['idServer' => $serverId, 'idWebapp' => $webappId];

        return $this->get("servers/$serverId/webapps/$webappId/git");

        // $data = $this->get("servers/$serverId/webapps/$webappId/git")['git'];
        
        // return $this->transformItem($data, Git::class, $extra);
    }

     /**
     * Clone Git repository.
     *
     * @param  string $serverId
     * @param  string $webappId
     * @param  array $data
     * @param  string $message (return message)
     * @return Git
     */
    public function webappGitClone(string $serverId, string $webappId, array $data, string &$message='')
    {
        $extra = ['idServer' => $serverId, 'idWebapp' => $webappId];
        $data_all = $this->post("servers/$serverId/webapps/$webappId/git", $data);
        $message = $data_all['message'];

        return $this->transformItem($data_all['git'], Git::class, $extra);
    }

    /**
     * Git change branch.
     *
     * @param  string $serverId
     * @param  string $webappId
     * @param  string $gitId
     * @param  string $branch
     * @return string
     */
    public function webappGitChangeBranch(string $serverId, string $webappId, string $gitId, string $branch)
    {
        $data = ['branch' => $branch];

        return $this->patch("servers/$serverId/webapps/$webappId/git/$gitId/branch", $data)['message'];
    }

    /**
     * Git customize deploymnet.
     *
     * @param  string $serverId
     * @param  string $webappId
     * @param  string $gitId
     * @param  array $data
     * @return string
     */
    public function webappGitCustomDeployment(string $serverId, string $webappId, string $gitId, array $data)
    {

        return $this->patch("servers/$serverId/webapps/$webappId/git/$gitId/script", $data)['message'];
    }

    /**
     * Git force deploy.
     *
     * @param  string $serverId
     * @param  string $webappId
     * @param  string $gitId
     * @return string
     */
    public function webappGitForceDeploy(string $serverId, string $webappId, string $gitId)
    {

        return $this->put("servers/$serverId/webapps/$webappId/git/$gitId/script")['message'];
    }


    /**
     * Delete Git repository.
     *
     * @param  string $serverId
     * @param  string $webappId
     * @param  string $gitId
     * @param  string $repository
     * @return string
     */
    public function deleteWebAppGit(string $serverId, string $webappId, string $gitId, string $repository)
    {
        $data = ['repository' => $repository];

        return $this->delete("servers/$serverId/webapps/$webappId/git/$gitId", $data)['message'];
    }

    // Scripts
    /**
     * Get Script installer.
     *
     * @param  string $serverId
     * @param  string $webappId
     * @return array
     */
    public function webappScriptInstaller(string $serverId, string $webappId)
    {
        return $this->get("servers/$serverId/webapps/$webappId/installer");
    }

    /**
     * Install Script.
     *
     * @param  string $serverId
     * @param  string $webappId
     * @param  string $scriptName
     * @return string
     */
    public function installWebAppScript(string $serverId, string $webappId, string $scriptName)
    {
        $data = ['scriptName' => $scriptName];

        return $this->post("servers/$serverId/webapps/$webappId/installer", $data)['message'];
    }

    /**
     * Delete Script.
     *
     * @param  string $serverId
     * @param  string $webappId
     * @param  string $scriptId
     * @return string
     */
    public function deleteWebAppScript(string $serverId, string $webappId, string $scriptId)
    {
        $data = ['typeYes' => 'YES'];

        return $this->delete("servers/$serverId/webapps/$webappId/installer", $data)['message'];
    }

    //SSL
     /**
     * Get SSL.
     *
     * @param  string $serverId
     * @param  string $webappId
     * @return Ssl[]
     */
    public function webappSsl(string $serverId, string $webappId)
    {
        $extra = ['idServer' => $serverId, 'idWebapp' => $webappId];
        $data = $this->get("servers/$serverId/webapps/$webappId/ssl");

        return $this->transformItem($data, Ssl::class, $extra);
    }

     /**
     * Install SSL.
     *
     * @param  string $serverId
     * @param  string $webappId
     * @param  array $data
     * @param  string $message (return message)
     * @return Ssl
     */
    public function installSsl(string $serverId, string $webappId, array $data, string &$message='')
    {
        $data_all = $this->post("servers/$serverId/webapps/$webappId/ssl", $data);

        $message = $data_all['message'];
        $ssl = new Ssl($data_all['ssl'], $this);

        return $ssl;
    }

     /**
     * Redeploy SSL. (Let’s Encrypt only)
     *
     * @param  string $serverId
     * @param  string $webappId
     * @return string
     */
    public function redeploySsl(string $serverId, string $webappId)
    {
        return $this->patch("servers/$serverId/webapps/$webappId/ssl")['message'];
    }

    /**
     * Update SSL config.
     *
     * @param  string $serverId
     * @param  string $webappId
     * @param  string $sslId
     * @param  array $data
     * @return string
     */
    public function updateSsl(string $serverId, string $webappId, string $sslId, array $data)
    {
        return $this->patch("servers/$serverId/webapps/$webappId/ssl/$sslId", $data)['message'];
    }

        /**
     * Delete SSL.
     *
     * @param  string $serverId
     * @param  string $webappId
     * @param  string $sslId
     * @return string
     */
    public function deleteSsl(string $serverId, string $webappId, string $sslId)
    {
        return $this->delete("servers/$serverId/webapps/$webappId/ssl/$sslId")['message'];
    }

    // Settings
    /**
     * Get settings of webapps (not documented)
     *
     * @param  string $serverId
     * @param  string $webappId
     * @return array
     */
    public function webappSettings(string $serverId, string $webappId)
    {
        return $this->get("servers/$serverId/webapps/$webappId/settings");
    }

    /**
     * Change webapp settings: PHP version.
     *
     * @param  string $serverId
     * @param  string $webappId
     * @param  string $phpVer
     * @return string
     */
    public function setWebappPhpVer(string $serverId, string $webappId, string $phpVer)
    {
        $data = ['phpVersion' => $phpVer];

        return $this->patch("servers/$serverId/webapps/$webappId/settings/phpversion", $data)['message'];
    }


    /**
     * Change webapp settings: PHP-FPM, NGiNX.
     *
     * @param  string $serverId
     * @param  string $webappId
     * @param  array $data
     * @return string
     */
    public function setWebappPhpFrmNginx(string $serverId, string $webappId, array $data)
    {
        return $this->patch("servers/$serverId/webapps/$webappId/settings/phpfpmnginx", $data)['message'];
    }

    // Log
    /**
     * Get the webapp log.
     *
     * @param  string $serverId
     * @param  string $webappId
     * @return Log[]
     */
    public function webappLog(string $serverId, string $webappId)
    {
        $extra = ['idServer' => $serverId, 'idWebapp' => $webappId];
        $data = $this->getAllData("servers/$serverId/webapps/$webappId/log");

        return $this->transformCollection($data, Log::class, $extra);
    }

}
