<?php

class UserResponseDTO {
    public function __construct(
        private int $id,
        private string $name,
        private string $email,
        private ?string $birthday
    ){}

    public function __toString(): string {
        return json_encode([
            'user_id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'birthday' => $this->birthday
        ], JSON_PRETTY_PRINT);
    }
} 