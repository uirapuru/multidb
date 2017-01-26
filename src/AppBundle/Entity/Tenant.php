<?php
namespace AppBundle\Entity;

class Tenant
{
    /** @var  string */
    protected $id;

    /** @var  string */
    protected $dbname;

    /** @var  string */
    protected $username;

    /** @var  string */
    protected $password;

    /** @var  string */
    protected $server;

    /**
     * Tenant constructor.
     * @param string $database
     * @param string $username
     * @param string $password
     * @param string $server
     */
    public function __construct($id, $database, $username, $password, $server)
    {
        $this->id = $id;
        $this->dbname = $database;
        $this->username = $username;
        $this->password = $password;
        $this->server = $server;
    }

    public static function fromArray($array)
    {
        return new self($array['id'], $array['database'], $array['username'], $array['password'], $array['server']);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getDatabase()
    {
        return $this->dbname;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getServer()
    {
        return $this->server;
    }
}
