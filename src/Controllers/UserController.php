<?php

namespace App\Controllers;

use App\Models\UserModel;
use DI\Annotation\Inject;
use Pecee\Http\Response;
use Pecee\SimpleRouter\SimpleRouter;

class UserController extends AbstractController
{
    /**
     * @Inject
     * @var UserModel
     */
    private $userModel;

    /**
     * @return Response|string
     */
    public function login()
    {
        if (SimpleRouter::request()->getMethod() === "post") {
            $userName = $this->inputHandler->post('username')->getValue();
            $password = $this->inputHandler->post('password')->getValue();
            $user = $this->userModel->getByUserNameAndPassword($userName, $password);
            if ($user !== null) {
                $_SESSION['user'] = $user;

                SimpleRouter::response()->redirect($this->baseRoute.'?page=1');

                return SimpleRouter::response();
            }

            return $this->render('login.html.twig', ['error' => 'Введены неверные данные']);
        }

        return $this->render('login.html.twig', []);
    }

    /**
     * @return Response
     */
    public function logout(): Response
    {
        if (isset($_SESSION['user'])) {
            unset($_SESSION['user']);
        }
        SimpleRouter::response()->redirect($this->baseRoute.'?page=1');

        return SimpleRouter::response();
    }
}
