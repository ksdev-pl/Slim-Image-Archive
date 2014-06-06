<?php

class Image extends ActiveRecord
{
    /** @type int $albumId */
    private $albumId;

    /** @type string $name */
    private $name;

    /** @type string $description */
    private $description;

    /** @type string $created */
    private $created;

    /** @type string $updated */
    private $updated;

    /** @type array $mimeTypes  Allowed extensions & related mime types */
    public static $mimeTypes = [
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif'
    ];

    /**
     * Get an array of allowed filename extensions
     *
     * @param bool $string  If true, return a string of filename extensions joined with '|'
     *
     * @return array|string
     */
    public static function getAllowedExtensions($string = false)
    {
        $allowedExtensions = array_keys(self::$mimeTypes);

        if ($string == true) {
            $allowedExtensions = implode('|', $allowedExtensions);
        }

        return $allowedExtensions;
    }

    /**
     * Show Image
     *
     * @param bool $thumb  Default false. Set to true to show thumbnail
     */
    public function show($thumb = false) {

        $imgPath = $thumb ? '/files/thumbs/' : '/files/';
        $fullPath = ROOT . $imgPath . $this->getName();

        if (file_exists($fullPath)) {
            $fileExtension = pathinfo($this->getName(), PATHINFO_EXTENSION);
            if (isset(self::$mimeTypes[$fileExtension])) {
                $mimeType = self::$mimeTypes[$fileExtension];
            }
            else {
                $mimeType = 'application/octet-stream';
            }
            header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 3600));
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($fullPath)) . ' GMT', true, 200);
            header('Content-type:' . $mimeType);
            readfile($fullPath);
            exit();
        }
    }

    /**
     * Insert Image record into DB
     */
    public function insert()
    {
        $query = 'INSERT INTO images (album_id, name, updated) VALUES (:albumId, :name, NOW())';
        $rowCount = $this->db->insert($query, [
            ':albumId' => $this->getAlbumId(),
            ':name' => $this->getName()
        ]);
    }

    /**
     * Update Image record in DB
     */
    public function update()
    {
        $query = 'UPDATE images SET description = :description, updated = NOW() WHERE id = :id';
        $rowCount = $this->db->update($query, [':description' => $this->getDescription(), ':id' => $this->getId()]);
    }

    /**
     * Delete Image record from DB & remove related image files
     */
    public function delete()
    {
        $query = 'DELETE FROM images WHERE id = :id';
        $rowCount = $this->db->delete($query, [':id' => $this->getId()]);

        if (file_exists(ROOT . '/files/' . $this->getName())) {
            unlink(ROOT . '/files/' . $this->getName());
        }
        if (file_exists(ROOT . '/files/thumbs/' . $this->getName())) {
            unlink(ROOT . '/files/thumbs/' . $this->getName());
        }
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
     * @param int $albumId
     */
    public function setAlbumId($albumId)
    {
        $this->checkIfEmpty($albumId);
        $this->albumId = $albumId;
    }

    /**
     * @return int
     */
    public function getAlbumId()
    {
        return $this->albumId;
    }

    /**
     * @param string $name  Name corresponding to image file with extension
     *
     * @throws FieldValidationException  If extension is not allowed - see $mimeTypes
     */
    public function setName($name)
    {
        $this->checkIfEmpty($name);

        $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        if (!in_array($extension, self::getAllowedExtensions())) {
            throw new FieldValidationException('Wrong name of a file');
        }

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
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
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