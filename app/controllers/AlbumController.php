<?php

class AlbumController extends BaseController
{
    /**
     * Show list of all Albums in Category
     *
     * @param int $categoryId
     */
    public function index($categoryId)
    {
        $category = $this->getCategoryFinder()->findOneBy('id', $categoryId);
        $albums = $this->getAlbumFinder()->findAllBy('category_id', $categoryId);
        $this->slim->render('album/index.html.twig', [
            'category' => $category,
            'albums' => $albums,
            'flash' => $this->getSessionFlash(),
            'sessionUser' => $this->getSessionUser()
        ]);
    }

    /**
     * Create new Album
     *
     * Also updates Category modification time
     *
     * @param int $categoryId
     */
    public function create($categoryId)
    {
        $category = $this->getCategoryFinder()->findOneBy('id', $categoryId);

        if ($this->slim->request->isGet()) {
            $this->slim->render('album/create.html.twig', [
                'category' => $category,
                'sessionUser' => $this->getSessionUser()
            ]);
        }
        elseif ($this->slim->request->isPost()) {
            $name = $_POST['name'];
            $newAlbum = new Album($this->slim->db);
            $newAlbum->setCategoryId($category->getId());
            $newAlbum->setName($name);
            $newAlbum->insert();

            $category->update();

            $this->slim->flash('success', 'Album created');
            $this->slim->redirect('/categories/' . $categoryId . '/albums');
        }
    }

    /**
     * Update Album name
     *
     * Also updates Category modification time
     *
     * @param int $categoryId
     * @param int $albumId
     */
    public function update($categoryId, $albumId)
    {
        $category = $this->getCategoryFinder()->findOneBy('id', $categoryId);
        $album = $this->getAlbumFinder()->findOneBy('id', $albumId);

        if ($this->slim->request->isGet()) {
            $this->slim->render('album/update.html.twig', [
                'category' => $category,
                'album' => $album,
                'sessionUser' => $this->getSessionUser()
            ]);
        }
        elseif ($this->slim->request->isPost()) {
            $name = $_POST['name'];
            $album->setName($name);
            $album->update();

            $category->update();

            $this->slim->flash('success', 'Album updated');
            $this->slim->redirect('/categories/' . $categoryId . '/albums');
        }
    }

    /**
     * Delete Album & related Images
     *
     * Also updates Category modification time
     *
     * @param int $categoryId
     * @param int $albumId
     */
    public function delete($categoryId, $albumId)
    {
        $category = $this->getCategoryFinder()->findOneBy('id', $categoryId);
        $album = $this->getAlbumFinder()->findOneBy('id', $albumId);

        if ($this->slim->request->isGet()) {
            $this->slim->render('album/delete.html.twig', [
                'category' => $category,
                'album' => $album,
                'sessionUser' => $this->getSessionUser()
            ]);
        }
        elseif ($this->slim->request->isPost()) {
            $images = $this->getImageFinder()->findAllBy('album_id', $albumId);
            foreach ($images as $image) {
                $image->delete();
            }
            $album->delete();

            $category->update();

            $this->slim->flash('success', 'Album deleted');
            $this->slim->redirect('/categories/' . $categoryId . '/albums');
        }
    }
}