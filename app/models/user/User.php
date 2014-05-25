<?php

class User extends ActiveRecord
{
    /** @type string $email */
    private $email;

    /** @type string $password */
    private $password;

    /** @type string $role */
    private $role;

    /** @type string $created */
    private $created;

    /** @type string $updated */
    private $updated;

    /** Numeric equivalent of user roles */
    const ADMIN = 1, EXTENDED = 2, NORMAL = 3;

    /**
     * Insert User record into DB
     */
    public function insert()
    {
        $query = 'INSERT INTO users (email, password, role, updated) VALUES (:email, :password, :role, NOW())';
        $rowCount = $this->db->insert($query, [
            ':email' => $this->getEmail(),
            ':password' => $this->getPassword(),
            ':role' => $this->getRole()
        ]);
    }

    /**
     * Update User record in DB
     */
    public function update()
    {
        $query = 'UPDATE users SET email = :email, password = :password, role = :role, updated = NOW() WHERE id = :id';
        $rowCount = $this->db->update($query, [
            ':id' => $this->getId(),
            ':email' => $this->getEmail(),
            ':password' => $this->getPassword(),
            ':role' => $this->getRole()
        ]);
    }

    /**
     * Delete User record from DB
     */
    public function delete()
    {
        $query = 'DELETE FROM users WHERE id = :id';
        $rowCount = $this->db->delete($query, [':id' => $this->getId()]);
    }

    // Accessors

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
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->checkIfEmpty($email);
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

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
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->checkIfEmpty($password);
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $role
     */
    public function setRole($role)
    {
        $this->checkIfEmpty($role);
        $this->role = $role;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
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