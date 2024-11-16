<?php

class UserUpdateDTO
 {
    public function __construct(
        private int $id,
        private ?string $name,
        private ?string $email,
        private ?string $password,
        private ?string $birthday
    ){}

    public function getId() {return $this->id;}
    public function getName() {return $this->name;}
    public function getEmail() {return $this->email;}
    public function getPassword() {return $this->password;}
    public function getBirthday() {return $this->birthday;}


    public function __toString(): string {
        return json_encode([
            'user_id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'birthday' => $this->birthday
        ], JSON_PRETTY_PRINT);
    }
} 