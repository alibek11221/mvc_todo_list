<?php

namespace App\Models;

use PDO;

class TodoModel extends AbstractModel
{
    /**
     * @param int $id
     *
     * @return array
     */
    public function get(int $id): array
    {
        $query = 'SELECT * FROM todos WHERE id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch();
    }

    /**
     * @param int $id
     * @param int $isDone
     */
    public function updateTodoStatus(int $id, int $isDone): void
    {
        $query = 'UPDATE todos SET done = :done  WHERE id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id, 'done' => $isDone]);
    }

    /**
     * @param int $pageLength
     *
     * @return int
     */
    public function getCountOfPages(int $pageLength): int
    {
        $query = 'SELECT COUNT(*) FROM todos';
        $countOfTodos = (int)$this->db->query($query)->fetchColumn();
        $countOfPages = $countOfTodos / $pageLength;
        if ((int)$countOfPages < $countOfPages) {
            $countOfPages = (int)$countOfPages + 1;
        }

        return (int)$countOfPages;
    }

    /**
     * @param int    $page
     * @param int    $pageLength
     * @param string $sort
     * @param string $type
     *
     * @return array
     */
    public function getAll(int $page, int $pageLength, string $sort, string $type): array
    {
        $start = $pageLength * ($page - 1);
        $query = 'SELECT * FROM todos';
        $query .= $this->getSortingQuery($sort, $type);
        $query .= ' LIMIT :page, :length';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam('page', $start, PDO::PARAM_INT);
        $stmt->bindParam('length', $pageLength, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * @param string $sort
     * @param string $type
     *
     * @return string
     */
    public function getSortingQuery(string $sort, string $type): string
    {
        if (!empty($sort) && !empty($type)) {
            return sprintf(' ORDER BY %s %s', $sort, $type);
        }

        return '';
    }

    /**
     * @param string $name
     * @param string $email
     * @param string $text
     * @param int    $done
     *
     * @return int
     */
    public function saveTodo(string $name, string $email, string $text, int $done): int
    {
        $query = 'INSERT INTO todos(`name`, `email`, `text`, `done`) VALUES(:name,:email,:text, :done)';
        $stmt = $this->db->prepare($query);
        $stmt->execute(['name' => $name, 'email' => $email, 'text' => $text, 'done' => $done]);

        return (int)$this->db->lastInsertId();
    }

    /**
     * @param int    $id
     * @param string $userName
     * @param string $email
     * @param string $text
     * @param int    $done
     */
    public function update(int $id, string $userName, string $email, string $text, int $done): void
    {
        $query
            = 'UPDATE todos SET name = :name, email = :email, text = :text, done = :done, changed = 1 WHERE id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id, 'name' => $userName, 'email' => $email, 'text' => $text, 'done' => $done]);
    }
}
