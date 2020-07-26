<?php

namespace App\Models;

class UserModel extends AbstractModel
{

    /**
     * @param string $userName
     * @param string $password
     *
     * @return array|null
     */
    public function getByUserNameAndPassword(string $userName, string $password): ?array
    {
        $query = 'SELECT * FROM users WHERE name = :name AND password = :password';
        $stmt = $this->db->prepare($query);
        $stmt->execute(['name' => $userName, 'password' => $password]);
        if ($user = $stmt->fetch()) {
            return $user;
        }

        return null;
    }
}
