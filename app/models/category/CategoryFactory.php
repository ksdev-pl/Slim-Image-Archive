<?php

class CategoryFactory
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
     * Get new Category
     *
     * @return Category
     */
    public function getInstance()
    {
        return new Category($this->db, new AlbumFactory($this->db));
    }
}