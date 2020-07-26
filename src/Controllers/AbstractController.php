<?php

namespace App\Controllers;

use App\Core\Db;
use Pecee\Http\Input\InputHandler;
use Twig\Environment;

abstract class AbstractController
{
    /**
     * @var Db
     */
    protected $db;
    /**
     * @var InputHandler
     */
    protected $inputHandler;
    /**
     * @var string
     */
    protected $baseRoute;
    /**
     * @var Environment
     */
    private $view;

    /**
     * AbstractController constructor.
     *
     * @param InputHandler $inputHandler
     * @param Db           $db
     * @param Environment  $view
     */
    public function __construct(InputHandler $inputHandler, Db $db, Environment $view)
    {
        $this->inputHandler = $inputHandler;
        $this->db = $db;
        $this->view = $view;
        $this->baseRoute = $_ENV['DIRECTORY_ROOT'] ?? '';
    }

    /**
     * @param string $template
     * @param array  $params
     *
     * @return string
     */
    protected function render(string $template, array $params): string
    {
        $params['baseroute'] = $this->baseRoute;
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $this->view->addGlobal('user', $user);
        }
        $page = $params['page'] ?? '';
        $this->view->addGlobal('page', $page);

        return $this->view->load($template)->render($params);
    }
}
