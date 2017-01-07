<?php

namespace App\User;

use App\Mapper;

class UserMapper extends Mapper
{
    public function getUser($email)
    {
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user) {
            return new UserEntity($user);
        }
        
        return false;
    }
}
