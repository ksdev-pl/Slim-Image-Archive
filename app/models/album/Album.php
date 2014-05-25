<?php

class Album extends ActiveRecord
{
    /** @type int $categoryId */
    private $categoryId;

    /** @type string $name */
    private $name;

    /** @type string $created */
    private $created;

    /** @type string $updated */
    private $updated;

    /**
     * Insert Album record into DB
     */
    public function insert()
    {
        $query = 'INSERT INTO albums (category_id, name, updated) VALUES (:categoryId, :name, NOW())';
        $rowCount = $this->db->insert($query, [
            ':categoryId' => $this->getCategoryId(),
            ':name' => $this->getName()
        ]);
    }

    /**
     * Update Album record in DB
     */
    public function update()
    {
        $query = 'UPDATE albums SET name = :name, updated = NOW() WHERE id = :id';
        $rowCount = $this->db->update($query, [':name' => $this->getName(), ':id' => $this->getId()]);
    }

    /**
     * Delete Album record from DB
     */
    public function delete()
    {
        $query = 'DELETE FROM albums WHERE id = :id';
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
     * @param int $categoryId
     */
    public function setCategoryId($categoryId)
    {
        $this->checkIfEmpty($categoryId);
        $this->categoryId = $categoryId;
    }

    /**
     * @return int
     */
    public function getCategoryId()
    {
        return $this->categoryId;
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