<?php

interface ChatRepository {
    public function findById($id): Chat;
    public function create(array $message, $args): Chat;
    public function update(Chat $chat, array $message): Chat;
    public function delete($id): bool;
}