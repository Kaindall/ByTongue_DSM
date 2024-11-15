<?php

class UserService {
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function findById($id) {
        return $this->userRepository->findById($id);
    }

    public function create($data) {
        $mapper = new UserMapper();
        $user = $mapper->map($data);
        $result = $this->userRepository->create($user);
        return $result;
    }

    public function update() {
        $result = $this->userRepository->update();

        return $result;
    }

    public function delete() {
        $result = $this->userRepository->delete();

        return $result;
    }
}