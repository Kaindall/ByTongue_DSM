<?php

class UserChat {
    public function __construct(
        private int $user_id,
        private string $chat_id,
        private ?string $title,
        private ?string $created_dt,
        private ?string $last_updt_dt,
    ) {}
}