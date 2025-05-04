<?php

class UserService {

    public function __construct(
        private UserMapper $userMapper, 
        private UserRepository $userRepository, 
        private ?UserChatsRepository $userChatsRepository) {
    }

    public function findById($id): UserResponse {
        if ($id != $_SESSION['user']->getId()) throw new UnauthorizedAccessException;
        $user = $this->userMapper->toUserResponse($this->userRepository->findById($id));
        return $user;
    }

    public function findChats($id) {
        if ($id != $_SESSION['user']->getId()) throw new UnauthorizedAccessException;
        return json_encode($this->userChatsRepository->findChats($id));
    }

    public function create($data): int {
        $user = $this->userMapper->map($data);
        $result = $this->userRepository->create($user);
        return $result;
    }

    public function update($id, $data): bool {
        if ($id != $_SESSION['user']->getId()) throw new UnauthorizedAccessException;
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