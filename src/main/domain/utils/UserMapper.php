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

    public function updateMap($id, $data): UserUpdateDTO {
        $data = json_decode($data, true);
        $formattedDate = null;
        if ($data['birthday']) {
            $dateParser = new DateParser();
            $formattedDate = $dateParser->validate($data['birthday']);
        }
        return new UserUpdateDTO(
            $id,
            $data['name'] ?? null,
            $data['email'] ?? null,
            $data['password'] ?? null,
            $formattedDate
        );
    }

    public function toUserResponse(User $user): UserResponseDTO {
        $formattedDate = null;
        if ($user->getBirthday()) {
            $dateParser = new DateParser();
            $formattedDate = $dateParser->validate($user->getBirthday());
        }
        return new UserResponseDTO(
            $user->getId(),
            $user->getName(),
            $user->getEmail(),
            $formattedDate
        );
    }
}