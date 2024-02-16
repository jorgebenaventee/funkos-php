<?php

namespace models;

class User
{
    private $id;
    private $username;
    private $password;
    private $roles;
    private $created_at;
    private $updated_at;

    /**
     * @param $id
     * @param $username
     * @param $password
     * @param $roles
     */
    public function __construct($id, $username, $password, $roles)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->roles = $roles;
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __set(string $name, $value): void
    {
        $this->$name = $value;
    }

}