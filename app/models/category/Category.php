<?php

class Category extends ActiveRecord
{
    /** @type string $name */
    private $name;

    /** @type string $created */
    private $created;

    /** @type string $updated */
    private $updated;

    /**
     * Insert Category record into DB
     */
    public function insert()
    {
        $query = 'INSERT INTO categories (name, updated) VALUES (:name, NOW())';
        $rowCount = $this->db->insert($query, [':name' => $this->getName()]);
    }

    /**
     * Update Category record in DB
     */
    public function update()
    {
        $query = 'UPDATE categories SET name = :name, updated = NOW() WHERE id = :id';
        $rowCount = $this->db->update($query, [':name' => $this->getName(), ':id' => $this->getId()]);
    }

    /**
     * Delete Category record from DB
     */
    public function delete()
    {
        $query = 'DELETE FROM categories WHERE id = :id';
        $rowCount = $this->db->delete($query, [':id' => $this->getId()]);
    }

    // Accessors

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->checkIfEmpty($id);
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->checkIfEmpty($name);
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return string
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param string $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * @return string
     */
    public function getUpdated()
    {
        return $this->updated;
    }

}