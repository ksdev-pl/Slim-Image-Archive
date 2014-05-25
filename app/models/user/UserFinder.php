<?php

class UserFinder
{
    /** @type DB $db */
    private $db;

    /** @type UserFactory $userFactory */
    private $userFactory;

    /**
     * @param DB $db
     * @param UserFactory $userFactory
     */
    public function __construct(DB $db, UserFactory $userFactory)
    {
        $this->db = $db;
        $this->userFactory = $userFactory;
    }

    /**
     * Get single User by field value
     *
     * @param string $column  Name of table field
     * @param string $value  Value of table field
     *
     * @return User|null
     * @throws RecordNotFoundException  Only if field = id and no record found
     */
    public function findOneBy($column, $value)
    {
        $query = 'SELECT * FROM users WHERE ' . $column . ' = :value';
        $userRow = $this->db->selectOne($query, [':value' => $value]);

        if (($column == 'id') && (empty($userRow))) {
            throw new RecordNotFoundException('User not found');
        }

        $userObject = null;
        if (!empty($userRow)) {
            $userObject = $this->load($userRow);
        }
        return $userObject;
    }

    /**
     * Get all Users
     *
     * @return User[]
     */
    public function findAll()
    {
        $query = 'SELECT * FROM users';
        $usersRows = $this->db->select($query);

        $usersObjects = [];
        foreach ($usersRows as $userRow) {
            $userObject = $this->load($userRow);
            $usersObjects[] = $userObject;
        }

        return $usersObjects;
    }

    /**
     * Hydrate User object with data from DB
     *
     * @param array $userRow
     *
     * @return User
     */
    private function load($userRow)
    {
        $userObject = $this->userFactory->getInstance();
        $userObject->setId($userRow['id']);
        $userObject->setEmail($userRow['email']);
        $userObject->setPassword($userRow['password']);
        $userObject->setRole($userRow['role']);
        $userObject->setCreated($userRow['created']);
        $userObject->setUpdated($userRow['updated']);

        return $userObject;
    }
}