<?php

interface ChatRepository {
    public function findById($id): Chat;
    public function create(Chat $chat): Chat;
    public function update(Chat $chat, array $message): bool;
    public function delete($id): bool;
    public function deleteMessage(string $chatId, string $msgId): bool;
}