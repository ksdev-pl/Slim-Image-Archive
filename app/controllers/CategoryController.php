<?php

class CategoryController extends BaseController
{
    /**
     * Show list of all Categories
     */
    public function index()
    {
        $categories = $this->getCategoryFinder()->findAll();
        $this->slim->render('category/index.html.twig', [
            'categories' => $categories,
            'flash' => $this->getSessionFlash(),
            'sessionUser' => $this->getSessionUser()
        ]);
    }

    /**
     * Create new Category
     */
    public function create()
    {
        if ($this->slim->request->isGet()) {
            $this->slim->render('category/create.html.twig', ['sessionUser' => $this->getSessionUser()]);
        }
        elseif ($this->slim->request->isPost()) {
            $name = $_POST['name'];
            $newCategory = new Category($this->slim->db);
            $newCategory->setName($name);
            $newCategory->insert();

            $this->slim->flash('success', 'Category created');
            $this->slim->redirect('/categories');
        }
    }

    /**
     * Update Category name
     *
     * @param int $categoryId
     */
    public function update($categoryId)
    {
        $category = $this->getCategoryFinder()->findOneBy('id', $categoryId);

        if ($this->slim->request->isGet()) {
            $this->slim->render('category/update.html.twig', [
                'category' => $category,
                'sessionUser' => $this->getSessionUser()
            ]);
        }
        elseif ($this->slim->request->isPost()) {
            $name = $_POST['name'];
            $category->setName($name);
            $category->update();

            $this->slim->flash('success', 'Category updated');
            $this->slim->redirect('/categories');
        }
    }

    /**
     * Delete Category, related Albums & Images
     *
     * @param int $categoryId
     */
    public function delete($categoryId)
    {
        $category = $this->getCategoryFinder()->findOneBy('id', $categoryId);

        if ($this->slim->request->isGet()) {
            $this->slim->render('category/delete.html.twig', [
                'category' => $category,
                'sessionUser' => $this->getSessionUser()
            ]);
        }
        elseif ($this->slim->request->isPost()) {
            $albums = $this->getAlbumFinder()->findAllBy('category_id', $categoryId);
            foreach ($albums as $album) {
                $images = $this->getImageFinder()->findAllBy('album_id', $album->getId());
                foreach ($images as $image) {
                    $image->delete();
                }
                $album->delete();
            }
            $category->delete();

            $this->slim->flash('success', 'Category deleted');
            $this->slim->redirect('/categories');
        }
    }
}