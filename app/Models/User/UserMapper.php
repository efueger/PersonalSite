<?php

namespace App\Models\User;

use App\Models\BaseMapper;

class UserMapper extends BaseMapper
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
