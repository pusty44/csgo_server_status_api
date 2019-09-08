<?php
/**
 * Created by PhpStorm.
 * User: pusty
 * Date: 10.04.2018
 * Time: 22:52
 */

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="v1_servers")
 */
class Servers {
    /**
     * @ORM\Column(type="integer", length=11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $serverId;
    /**
     * @ORM\Column(type="string")
     */
    private $type;
    /**
     * @ORM\Column(type="string")
     */
    private $host;
    /**
     * @ORM\Column(type="integer", length=8)
     */
    private $port;
    /**
     * @ORM\Column(type="string")
     */
    private $servername;
    /**
     * @ORM\Column(type="boolean")
     */
    private $status;
    /**
     * @ORM\Column(type="integer", length=3)
     */
    private $percent;
    /**
     * @ORM\Column(type="string")
     */
    private $percent_color;
    /**
     * @ORM\Column(type="integer", length=4)
     */
    private $maxPlayers;
    /**
     * @ORM\Column(type="integer", length=4)
     */
    private $currentPlayers;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $currentMap;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $queryLogin;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $queryPwd;
    /**
     * @ORM\Column(type="integer", length=7, nullable=true)
     */
    private $queryport;

    /**
     * @return mixed
     */
    public function getServerId()
    {
        return $this->serverId;
    }

    /**
     * @param mixed $serverId
     */
    public function setServerId($serverId): void
    {
        $this->serverId = $serverId;
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param mixed $host
     */
    public function setHost($host): void
    {
        $this->host = $host;
    }

    /**
     * @return mixed
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param mixed $port
     */
    public function setPort($port): void
    {
        $this->port = $port;
    }

    /**
     * @return mixed
     */
    public function getServername()
    {
        return $this->servername;
    }

    /**
     * @param mixed $servername
     */
    public function setServername($servername): void
    {
        $this->servername = $servername;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getMaxPlayers()
    {
        return $this->maxPlayers;
    }

    /**
     * @param mixed $maxPlayers
     */
    public function setMaxPlayers($maxPlayers): void
    {
        $this->maxPlayers = $maxPlayers;
    }

    /**
     * @return mixed
     */
    public function getCurrentPlayers()
    {
        return $this->currentPlayers;
    }

    /**
     * @param mixed $currentPlayers
     */
    public function setCurrentPlayers($currentPlayers): void
    {
        $this->currentPlayers = $currentPlayers;
    }

    /**
     * @return mixed
     */
    public function getCurrentMap()
    {
        return $this->currentMap;
    }

    /**
     * @param mixed $currentMap
     */
    public function setCurrentMap($currentMap): void
    {
        $this->currentMap = $currentMap;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getQueryLogin()
    {
        return $this->queryLogin;
    }

    /**
     * @param mixed $queryLogin
     */
    public function setQueryLogin($queryLogin): void
    {
        $this->queryLogin = $queryLogin;
    }

    /**
     * @return mixed
     */
    public function getQueryPwd()
    {
        return $this->queryPwd;
    }

    /**
     * @param mixed $queryPwd
     */
    public function setQueryPwd($queryPwd): void
    {
        $this->queryPwd = $queryPwd;
    }

    /**
     * @return mixed
     */
    public function getQueryport()
    {
        return $this->queryport;
    }

    /**
     * @param mixed $queryport
     */
    public function setQueryport($queryport): void
    {
        $this->queryport = $queryport;
    }

    /**
     * @return mixed
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * @param mixed $percent
     */
    public function setPercent($percent): void
    {
        $this->percent = $percent;
    }

    /**
     * @return mixed
     */
    public function getPercentColor()
    {
        return $this->percent_color;
    }

    /**
     * @param mixed $percent_color
     */
    public function setPercentColor($percent_color): void
    {
        $this->percent_color = $percent_color;
    }


}