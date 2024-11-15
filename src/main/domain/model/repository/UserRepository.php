<?php

interface UserRepository {
    public function create(User $user): int;
    public function findById($id): UserResponseDTO;
    public function update();
    public function delete();
}