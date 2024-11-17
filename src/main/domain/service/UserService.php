<?php

class UserService {
    private UserRepository $userRepository;
    private UserMapper $userMapper;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
        $this->userMapper = new UserMapper();
    }

    public function findById($id): UserResponse {
        $user = $this->userMapper->toUserResponse($this->userRepository->findById($id));
        return $user;
    }

    public function create($data): int {
        $user = $this->userMapper->map($data);
        $result = $this->userRepository->create($user);
        return $result;
    }

    public function update($id, $data): bool {
        if ($id != $_SESSION['user']->getId()) return false;
        if ($data === null || empty($data)) {throw new EmptyBodyException;};
        $user = $this->userMapper->toUserUpdateRequest($id, $data);
        $result = $this->userRepository->update($user);
        return $result;
    }

    public function delete($id): bool {
        if ($id != $_SESSION['user']->getId()) return false;
        $result = $this->userRepository->delete($id);
        return $result;
    }
}