<?php

interface UserChatsRepository {
    public function findChats(int $id): array;
    public function createLink(int $userId, string $chatId): bool;
    public function refreshLink(string $chatId);
    public function deleteLink(string $chatId): bool;
    public function deleteAllLinks(string $userId): bool;
}