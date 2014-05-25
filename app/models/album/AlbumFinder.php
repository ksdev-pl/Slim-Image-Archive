<?php

class AlbumFinder
{
    /** @type DB $db */
    private $db;

    /** @type AlbumFactory $albumFactory */
    private $albumFactory;

    /**
     * @param DB $db
     * @param AlbumFactory $albumFactory
     */
    public function __construct(DB $db, AlbumFactory $albumFactory)
    {
        $this->db = $db;
        $this->albumFactory = $albumFactory;
    }

    /**
     * Get single Album by field value
     *
     * @param string $column  Name of table field
     * @param string $value  Value of table field
     *
     * @return Album|null
     * @throws RecordNotFoundException  Only if field = id and no record found
     */
    public function findOneBy($column, $value)
    {
        $query = 'SELECT * FROM albums WHERE ' . $column . ' = :value';
        $albumRow = $this->db->selectOne($query, [':value' => $value]);

        if (($column == 'id') && (empty($albumRow))) {
            throw new RecordNotFoundException('Album not found');
        }

        $albumObject = null;
        if (!empty($albumRow)) {
            $albumObject = $this->load($albumRow);
        }
        return $albumObject;
    }

    /**
     * Get all Albums by field value
     *
     * @param string $column  Name of table field
     * @param string $value  Value of table field
     *
     * @return Album[]
     */
    public function findAllBy($column, $value)
    {
        $query = 'SELECT * FROM albums WHERE ' . $column . ' = :value';
        $albumsRows = $this->db->select($query, [':value' => $value]);

        $albumsObjects = [];
        foreach ($albumsRows as $albumRow) {
            $albumObject = $this->load($albumRow);
            $albumsObjects[] = $albumObject;
        }

        return $albumsObjects;
    }

    /**
     * Hydrate Album object with data from DB
     *
     * @param array $albumRow
     *
     * @return Album
     */
    private function load($albumRow)
    {
        $albumObject = $this->albumFactory->getInstance();
        $albumObject->setId($albumRow['id']);
        $albumObject->setCategoryId($albumRow['category_id']);
        $albumObject->setName($albumRow['name']);
        $albumObject->setCreated($albumRow['created']);
        $albumObject->setUpdated($albumRow['updated']);

        return $albumObject;
    }
}