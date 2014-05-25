<?php

class UserFactory
{
    /** @type DB $db */
    protected $db;

    /**
     * @param DB $db
     */
    public function __construct(DB $db)
    {
        $this->db = $db;
    }

    /**
     * Get new User
     *
     * @return User
     */
    public function getInstance()
    {
        return new User($this->db, new UserFactory($this->db));
    }
}