<?php

interface UserRepository {
    public function create(User $user): int;
    public function findById(int $id): User;
    public function findByEmail(int $email): User;
    public function update(UserUpdateRequest $user): bool;
    public function delete(int $id): bool;
}