<?php

class ImageController extends BaseController
{
    /**
     * Show list of all Images in Album
     *
     * @param int $categoryId
     * @param int $albumId
     */
    public function index($categoryId, $albumId)
    {
        $category = $this->getCategoryFinder()->findOneBy('id', $categoryId);
        $album = $this->getAlbumFinder()->findOneBy('id', $albumId);
        $images = $this->getImageFinder()->findAllBy('album_id', $albumId);
        $this->slim->render('image/index.html.twig', [
            'category' => $category,
            'album' => $album,
            'images' => $images,
            'flash' => $this->getSessionFlash(),
            'sessionUser' => $this->getSessionUser()
        ]);
    }

    /**
     * Upload new Images
     *
     * Also updates Category & Album modification times
     *
     * @param int $categoryId
     * @param int $albumId
     */
    public function create($categoryId, $albumId)
    {
        $category = $this->getCategoryFinder()->findOneBy('id', $categoryId);
        $album = $this->getAlbumFinder()->findOneBy('id', $albumId);

        if ($this->slim->request->isGet()) {
            $this->slim->render('image/create.html.twig', [
                'category' => $category,
                'album' => $album,
                'sessionUser' => $this->getSessionUser(),
                'allowedExtensions' => Image::getAllowedExtensions(true)
            ]);
        }
        elseif ($this->slim->request->isPost()) {
            $uploadHandler = new CustomUploadHandler([
                'upload_dir' => ROOT . '/files/',
                'upload_url' => null,
                'access_control_allow_methods' => ['POST'],
                'accept_file_types' => '/\.(' . Image::getAllowedExtensions(true) . ')$/i',
                'image_library' => 0,
                'image_versions' => [
                    '' => ['auto_orient' => false],
                    'thumb' => [
                        'upload_dir' => ROOT . '/files/thumbs/',
                        'upload_url' => null,
                        'crop' => true,
                        'max_width' => 252,
                        'max_height' => 252
                    ]
                ]
            ]);

            $newImage = new Image($this->slim->db);
            $newImage->setAlbumId($album->getId());
            $newImage->setName($uploadHandler->getUploadedFileName());
            $newImage->insert();

            $album->update();
            $category->update();
        }
    }

    /**
     * Update Image description
     *
     * Also updates Category & Album modification times
     *
     * @param int $categoryId
     * @param int $albumId
     * @param int $imageId
     */
    public function update($categoryId, $albumId, $imageId)
    {
        $category = $this->getCategoryFinder()->findOneBy('id', $categoryId);
        $album = $this->getAlbumFinder()->findOneBy('id', $albumId);
        $image = $this->getImageFinder()->findOneBy('id', $imageId);

        if ($this->slim->request->isGet()) {
            $this->slim->render('image/update.html.twig', [
                'category' => $category,
                'album' => $album,
                'image' => $image,
                'sessionUser' => $this->getSessionUser()
            ]);
        }
        elseif ($this->slim->request->isPost()) {
            $description = $_POST['description'];
            $image->setDescription($description);
            $image->update();

            $album->update();
            $category->update();

            $this->slim->flash('success', 'Image updated');
            $this->slim->redirect('/categories/' . $categoryId . '/albums/' . $albumId . '/images');
        }
    }

    /**
     * Delete Image
     *
     * Also updates Category & Album modification times
     *
     * @param int $categoryId
     * @param int $albumId
     * @param int $imageId
     */
    public function delete($categoryId, $albumId, $imageId)
    {
        $category = $this->getCategoryFinder()->findOneBy('id', $categoryId);
        $album = $this->getAlbumFinder()->findOneBy('id', $albumId);
        $image = $this->getImageFinder()->findOneBy('id', $imageId);

        if ($this->slim->request->isGet()) {
            $this->slim->render('image/delete.html.twig', [
                'category' => $category,
                'album' => $album,
                'image' => $image,
                'sessionUser' => $this->getSessionUser()
            ]);
        }
        elseif ($this->slim->request->isPost()) {
            $image->delete();

            $album->update();
            $category->update();

            $this->slim->flash('success', 'Image deleted');
            $this->slim->redirect('/categories/' . $categoryId . '/albums/' . $albumId . '/images');
        }
    }

    /**
     * Show Image & thumbnail
     *
     * Shows thumbnail when URL parameter "thumb" is set
     *
     * @param int $categoryId
     * @param int $albumId
     * @param int $imageId
     */
    public function show($categoryId, $albumId, $imageId) {
        $image = $this->getImageFinder()->findOneBy('id', $imageId);

        if (isset($_GET['thumb'])) {
            $image->show(true);
        }
        else {
            $image->show();
        }
    }
}