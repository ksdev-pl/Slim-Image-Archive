<?php

abstract class ActiveRecord
{
    /** @type int $id  Id of record */
    protected $id;

    /** @type DB $db */
    protected $db;

    /**
     * @param DB $db
     */
    public function __construct(DB $db) {
        $this->db = $db;
    }

    /**
     * Insert record into DB
     */
    abstract function insert();

    /**
     * Update record in DB
     */
    abstract function update();

    /**
     * Delete record from DB
     */
    abstract function delete();

    /**
     * Checks if field is empty
     *
     * @param mixed $field
     *
     * @throws FieldValidationException
     */
    protected function checkIfEmpty($field) {
        if (empty($field)) {
            throw new FieldValidationException('All form fields must be filled in');
        }
    }
}