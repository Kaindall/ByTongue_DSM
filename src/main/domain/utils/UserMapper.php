<?php
require_once 'src\main\domain\model\exception\users\EmptyNameException.php';
require_once 'src\main\domain\model\exception\users\EmptyPasswordException.php';
require_once 'src\main\domain\model\exception\users\EmptyEmailException.php';


class UserMapper {
    public function map($data): User {
        return new User(
            null,
            $data['name'] ?? throw new EmptyNameException,
            $data['email'] ?? throw new EmptyEmailException,
            $data['password'] ?? throw new EmptyPasswordException,
            $data['birthday'] ?? null
        );
    }

    public function toUserUpdateRequest($id, $data): UserUpdateRequest {
        $data = json_decode($data, true);
        $formattedDate = null;
        if (isset($data['birthday'])) {
            $dateParser = new DateParser();
            $formattedDate = $dateParser->validate($data['birthday']);
        }
        return new UserUpdateRequest(
            $id,
            $data['name'] ?? null,
            $data['email'] ?? null,
            $data['password'] ?? null,
            $formattedDate
        );
    }

    public function toUserResponse(User $user): UserResponse {
        $formattedDate = null;
        if ($user->getBirthday()) {
            $dateParser = new DateParser();
            $formattedDate = $dateParser->validate($user->getBirthday());
        }
        return new UserResponse(
            $user->getId(),
            $user->getName(),
            $user->getEmail(),
            $formattedDate
        );
    }

    public function toUserAuthRequest($data): UserAuthRequest {
        $data = json_decode($data, true);
        return new UserAuthRequest(
            $data['email'] ?? throw new EmptyEmailException,
            $data['password'] ?? throw new EmptyPasswordException
        );
    }
}