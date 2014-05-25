<?php

class Auth
{
    /**
     * Store User authentication data in session
     *
     * Also regenerates session id to prevent session fixation
     *
     * @param User $user
     */
    public function signIn(User $user)
    {
        $_SESSION['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
        $userArray = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'role' => $user->getRole()
        ];
        $_SESSION['User'] = serialize($userArray);
        session_regenerate_id(true);
    }

    /**
     * Destroy User authentication session data
     */
    public function signOut()
    {
        $_SESSION = [];
        session_destroy();

        session_start();
    }

    /**
     * Check if User is signed in
     *
     * Also checks User-Agent header to prevent session hijacking
     *
     * @return bool  True if signed in, otherwise false
     */
    public function checkSession()
    {
        if (isset($_SESSION['User'])) {
            if ((isset($_SESSION['HTTP_USER_AGENT']))
                && ($_SESSION['HTTP_USER_AGENT'] === $_SERVER['HTTP_USER_AGENT'])) {

                return true;
            }
            else {
                $this->signOut();
            }
        }

        return false;
    }

    /**
     * Hash the password
     *
     * @param string $password
     *
     * @return string|false
     */
    public function hashPassword($password)
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);

        return $hash;
    }

    /**
     * Verify a password against a hash
     *
     * @param string $password
     * @param string $hash
     *
     * @return bool  True if verified, otherwise false
     */
    public function verifyPassword($password, $hash)
    {
        if (password_verify($password, $hash)) {
            return true;
        } else {
            return false;
        }
    }
}