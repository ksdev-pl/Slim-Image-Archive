<?php

class UserController extends BaseController
{
    /**
     * Show list of all Users
     */
    public function index()
    {
        $users = $this->getUserFinder()->findAll();
        $this->slim->render('user/index.html.twig', [
            'users' => $users,
            'flash' => $this->getSessionFlash(),
            'sessionUser' => $this->getSessionUser()
        ]);
    }

    /**
     * Create new User
     */
    public function create()
    {
        if ($this->slim->request->isGet()) {
            $this->slim->render('user/create.html.twig', ['sessionUser' => $this->getSessionUser()]);
        }
        elseif ($this->slim->request->isPost()) {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $role = $_POST['role'];

            $auth = new Auth();
            $hash = $auth->hashPassword($password);

            $newUser = new User($this->slim->db);
            $newUser->setEmail($email);
            $newUser->setPassword($hash);
            $newUser->setRole($role);
            $newUser->insert();

            $this->slim->flash('success', 'User created');
            $this->slim->redirect('/users');
        }
    }

    /**
     * Update User
     *
     * @param int $userId
     */
    public function update($userId)
    {
        $user = $this->getUserFinder()->findOneBy('id', $userId);

        if ($this->slim->request->isGet()) {
            $this->slim->render('user/update.html.twig', [
                'user' => $user,
                'sessionUser' => $this->getSessionUser()
            ]);
        }
        elseif ($this->slim->request->isPost()) {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $role = $_POST['role'];

            $auth = new Auth();
            $hash = $auth->hashPassword($password);

            $user->setEmail($email);
            $user->setPassword($hash);
            $user->getRole($role);
            $user->update();

            $this->slim->flash('success', 'User updated');
            $this->slim->redirect('/users');
        }
    }

    /**
     * Delete User
     *
     * @param int $userId
     */
    public function delete($userId)
    {
        $user = $this->getUserFinder()->findOneBy('id', $userId);

        if ($this->slim->request->isGet()) {
            $this->slim->render('user/delete.html.twig', [
                'user' => $user,
                'sessionUser' => $this->getSessionUser()
            ]);
        }
        elseif ($this->slim->request->isPost()) {
            $user->delete();

            $this->slim->flash('success', 'User deleted');
            $this->slim->redirect('/users');
        }
    }

    /**
     * Authenticate & sign User in
     */
    public function signIn()
    {
        if ($this->slim->request->isGet()) {
            $this->slim->render('signin.html.twig');
        }
        elseif ($this->slim->request->isPost()) {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = $this->getUserFinder()->findOneBy('email', $email);

            if (!empty($user)) {
                $auth = new Auth();
                $passwordVerified = $auth->verifyPassword($password, $user->getPassword());

                if ($passwordVerified === true) {
                    $auth->signIn($user);

                    $this->slim->flash('success', 'Signed In');
                    $this->slim->redirect('/categories');
                }
            }

            $this->slim->flash('error', 'Incorrect email or password');
            $this->slim->redirect('/signin');
        }
    }

    /**
     * Sign User out
     */
    public function signOut()
    {
        $auth = new Auth;
        $auth->signOut();

        $this->slim->flash('success', 'Signed Out');
        $this->slim->redirect('/signin');
    }
}