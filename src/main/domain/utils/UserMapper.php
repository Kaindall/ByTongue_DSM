<?php
require_once 'src\main\domain\model\exception\users\EmptyNameException.php';
require_once 'src\main\domain\model\exception\users\EmptyPasswordException.php';
require_once 'src\main\domain\model\exception\users\EmptyEmailException.php';

class UserMapper {
    public function map($data): User {
        return new User(
            null,
            $data['name'] ?? throw new EmptyNameException,
            $data['email'] ?? throw new EmptyEmailException,
            $data['password'] ?? throw new EmptyPasswordException,
            $data['birthday'] ?? null
        );
    }
}