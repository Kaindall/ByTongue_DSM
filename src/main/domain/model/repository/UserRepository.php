<?php

interface UserRepository {
    public function create(User $user): int;
    public function findById(int $id): User;
    public function update(UserUpdateDTO $user): bool;
    public function delete(int $id): bool;
}