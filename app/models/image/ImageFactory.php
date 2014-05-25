<?php

class ImageFactory
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
     * Get new Image
     *
     * @return Image
     */
    public function getInstance()
    {
        return new Image($this->db);
    }
}