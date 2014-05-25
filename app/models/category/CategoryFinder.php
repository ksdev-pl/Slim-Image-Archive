<?php

class CategoryFinder
{
    /** @type DB $db */
    private $db;

    /** @type CategoryFactory $categoryFactory */
    private $categoryFactory;

    /**
     * @param DB $db
     * @param CategoryFactory $categoryFactory
     */
    public function __construct(DB $db, CategoryFactory $categoryFactory)
    {
        $this->db = $db;
        $this->categoryFactory = $categoryFactory;
    }

    /**
     * Get single Category by field value
     *
     * @param string $column  Name of table field
     * @param string $value  Value of table field
     *
     * @return Category|null
     * @throws RecordNotFoundException  Only if field = id and no record found
     */
    public function findOneBy($column, $value)
    {
        $query = 'SELECT * FROM categories WHERE ' . $column . ' = :value';
        $categoryRow = $this->db->selectOne($query, [':value' => $value]);

        if (($column == 'id') && (empty($categoryRow))) {
            throw new RecordNotFoundException('Category not found');
        }

        $categoryObject = null;
        if (!empty($categoryRow)) {
            $categoryObject = $this->load($categoryRow);
        }
        return $categoryObject;
    }

    /**
     * Get all Categories
     *
     * @return Category[]
     */
    public function findAll()
    {
        $query = 'SELECT * FROM categories';
        $categoriesRows = $this->db->select($query);

        $categoriesObjects = [];
        foreach ($categoriesRows as $categoryRow) {
            $categoryObject = $this->load($categoryRow);
            $categoriesObjects[] = $categoryObject;
        }

        return $categoriesObjects;
    }

    /**
     * Hydrate Category object with data from DB
     *
     * @param array $categoryRow
     *
     * @return Category
     */
    private function load($categoryRow)
    {
        $categoryObject = $this->categoryFactory->getInstance();
        $categoryObject->setId($categoryRow['id']);
        $categoryObject->setName($categoryRow['name']);
        $categoryObject->setCreated($categoryRow['created']);
        $categoryObject->setUpdated($categoryRow['updated']);

        return $categoryObject;
    }
}