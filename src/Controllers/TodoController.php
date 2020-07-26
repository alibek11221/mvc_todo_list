<?php

namespace App\Controllers;

use App\Models\TodoModel;
use DI\Annotation\Inject;
use Pecee\SimpleRouter\SimpleRouter;

class TodoController extends AbstractController
{
    /**
     * @Inject
     * @var TodoModel
     */
    private $todoModel;

    public function create()
    {
        $requestParams = $this->getRequestValues();

        $this->todoModel->saveTodo(
            $requestParams['userName'],
            $requestParams['email'],
            $requestParams['text'],
            $requestParams['done']
        );
        SimpleRouter::response()->redirect($this->baseRoute.'?page=1&new=1');

        return SimpleRouter::response();
    }

    private function getRequestValues(): array
    {
        $output['userName'] = $this->inputHandler->post('username')->getValue();
        $output['email'] = $this->inputHandler->post('email')->getValue();
        $output['text'] = $this->inputHandler->post('text')->getValue();
        $output['done'] = $this->inputHandler->post('done') == 'on' ? 1 : 0;

        return $output;
    }

    public function update(int $id)
    {
        if ((SimpleRouter::request()->getMethod() === "post")) {
            $requestParams = $this->getRequestValues();
            $this->todoModel->update(
                $id,
                $requestParams['userName'],
                $requestParams['email'],
                $requestParams['text'],
                $requestParams['done']
            );
            SimpleRouter::response()->redirect($this->baseRoute.'?page=1');

            return SimpleRouter::response();
        }
        $params['todo'] = $this->todoModel->get($id);

        return $this->render('todo.html.twig', $params);
    }

    /**
     * @param int $id
     * @param int $isDone
     *
     */
    public function updateStatus(int $id, int $isDone): void
    {
        $this->todoModel->updateTodoStatus($id, $isDone);
    }

    /**
     * @return string
     */
    public function getAllByPage(): string
    {
        $page = $this->inputHandler->get('page') !== null ? (int)$this->inputHandler->get('page')->getValue() : 1;
        $sort = $this->inputHandler->get('sort') !== null ? $this->inputHandler->get('sort')->getValue() : 'name';
        $type = $this->inputHandler->get('type') !== null ? $this->inputHandler->get('type')->getValue() : 'DESC';
        $new = $this->inputHandler->get('new') !== null ? (int)$this->inputHandler->get('new')->getValue() : 0;
        $todos = $this->todoModel->getAll($page, 3, $sort, $type);
        $countOfPages = $this->todoModel->getCountOfPages(3);
        $sortQuery = sprintf("sort=%s&type=%s", $sort, $type);
        $params = [
            'todos'      => $todos,
            'pages'      => $countOfPages,
            'page'       => $page,
            'new'        => $new,
            'sortquery'  => $sortQuery,
            'typeofsort' => $type,
        ];

        return $this->render('index.html.twig', $params);
    }

}
