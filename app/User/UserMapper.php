<?php

namespace App\User;

use App\Mapper;

class UserMapper extends Mapper
{
    public function getUser($email)
    {
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $result = $stmt->execute(['email' => $email]);

        if ($result) {
            return new UserEntity($stmt->fetch());
        }
    }
}
