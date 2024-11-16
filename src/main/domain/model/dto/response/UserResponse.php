<?php

class UserResponse {
    public function __construct(
        private int $id,
        private string $name,
        private string $email,
        private ?string $birthday
    ){}

    public function getId(): int {return $this->id;}
    public function getName(): string {return $this->name;}
    public function getEmail(): string {return $this->email;}
    public function getBirthday(): string {return $this->birthday;}

    public function __toString(): string {
        return json_encode([
            'user_id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'birthday' => $this->birthday
        ], JSON_PRETTY_PRINT);
    }
} 