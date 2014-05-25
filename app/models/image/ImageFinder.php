<?php

class ImageFinder
{
    /** @type DB $db */
    private $db;

    /** @type ImageFactory $imageFactory */
    private $imageFactory;

    /**
     * @param DB $db
     * @param ImageFactory $imageFactory
     */
    public function __construct(DB $db, ImageFactory $imageFactory)
    {
        $this->db = $db;
        $this->imageFactory = $imageFactory;
    }

    /**
     * Get single Image by field value
     *
     * @param string $column  Name of table field
     * @param string $value  Value of table field
     *
     * @return Image|null
     * @throws RecordNotFoundException  Only if field = id and no record found
     */
    public function findOneBy($column, $value)
    {
        $query = 'SELECT * FROM images WHERE ' . $column . ' = :value';
        $imageRow = $this->db->selectOne($query, [':value' => $value]);

        if (($column == 'id') && (empty($imageRow))) {
            throw new RecordNotFoundException('Image not found');
        }

        $imageObject = null;
        if (!empty($imageRow)) {
            $imageObject = $this->load($imageRow);
        }
        return $imageObject;
    }

    /**
     * Get all Images by field value
     *
     * @param string $column  Name of table field
     * @param string $value  Value of table field
     *
     * @return Image[]
     */
    public function findAllBy($column, $value)
    {
        $query = 'SELECT * FROM images WHERE ' . $column . ' = :value';
        $imagesRows = $this->db->select($query, [':value' => $value]);

        $imagesObjects = [];
        foreach ($imagesRows as $imageRow) {
            $imageObject = $this->load($imageRow);
            $imagesObjects[] = $imageObject;
        }

        return $imagesObjects;
    }

    /**
     * Hydrate Image object with data from DB
     *
     * @param array $imageRow
     *
     * @return Image
     */
    private function load($imageRow)
    {
        $imageObject = $this->imageFactory->getInstance();
        $imageObject->setId($imageRow['id']);
        $imageObject->setAlbumId($imageRow['album_id']);
        $imageObject->setName($imageRow['name']);
        $imageObject->setDescription($imageRow['description']);
        $imageObject->setCreated($imageRow['created']);
        $imageObject->setUpdated($imageRow['updated']);

        return $imageObject;
    }
}