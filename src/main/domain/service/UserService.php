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

    public function update($id, $data) {
        if ($data === null || empty($data)) {throw new EmptyBodyException;}
        $mapper = new UserMapper();
        $user = $mapper->updateMap($id, $data);
        $result = $this->userRepository->update($user);
        return $result;
    }

    public function delete($id): bool {
        $result = $this->userRepository->delete($id);
        return $result;
    }
}