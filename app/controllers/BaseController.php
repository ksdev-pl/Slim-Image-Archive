<?php

class BaseController
{
    /** @type Slim\Slim $slim */
    protected $slim;

    /** @type CategoryFinder $categoryFinder */
    protected $categoryFinder;

    /** @type AlbumFinder $albumFinder */
    protected $albumFinder;

    /** @type ImageFinder $imageFinder */
    protected $imageFinder;

    /** @type UserFinder $userFinder */
    protected $userFinder;

    /**
     * Initialize with Slim framework instance
     */
    public function __construct()
    {
        $this->slim = Slim\Slim::getInstance();
    }

    /**
     * Get session flash information
     *
     * @return array|null
     */
    protected function getSessionFlash()
    {
        if(isset($_SESSION['slim.flash'])) {
            return $_SESSION['slim.flash'];
        }
        else {
            return null;
        }
    }

    /**
     * Get signed in User information
     *
     * @return array|null
     */
    protected function getSessionUser()
    {
        if(isset($_SESSION['User'])) {
            return unserialize($_SESSION['User']);
        }
        else {
            return null;
        }
    }

    /**
     * Get a finder of Category
     *
     * @return CategoryFinder
     */
    protected function getCategoryFinder()
    {
        if (!isset($this->categoryFinder)) {
            $this->categoryFinder = new CategoryFinder($this->slim->db, new CategoryFactory($this->slim->db));
        }
        return $this->categoryFinder;
    }

    /**
     * Get a finder of Album
     *
     * @return AlbumFinder
     */
    protected function getAlbumFinder()
    {
        if (!isset($this->albumFinder)) {
            $this->albumFinder = new AlbumFinder($this->slim->db, new AlbumFactory($this->slim->db));
        }
        return $this->albumFinder;
    }

    /**
     * Get a finder of Image
     *
     * @return ImageFinder
     */
    protected function getImageFinder()
    {
        if (!isset($this->imageFinder)) {
            $this->imageFinder = new ImageFinder($this->slim->db, new ImageFactory($this->slim->db));
        }
        return $this->imageFinder;
    }

    /**
     * Get a finder of User
     *
     * @return UserFinder
     */
    protected function getUserFinder()
    {
        if (!isset($this->userFinder)) {
            $this->userFinder = new UserFinder($this->slim->db, new UserFactory($this->slim->db));
        }
        return $this->userFinder;
    }
}