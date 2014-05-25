<?php

class AlbumFactory
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
     * Get new Album
     *
     * @return Album
     */
    public function getInstance()
    {
        return new Album($this->db);
    }
}