<?php
require_once 'src\main\domain\model\exception\users\EmptyNameException.php';
require_once 'src\main\domain\model\exception\users\EmptyPasswordException.php';
require_once 'src\main\domain\model\exception\users\EmptyEmailException.php';
require_once 'src\main\domain\model\exception\users\InvalidBirthdayFormatException.php';
require_once 'src\main\domain\model\exception\users\InvalidDateYearException.php';
require_once 'src\main\domain\model\exception\users\InvalidDateMonthException.php';
require_once 'src\main\domain\model\exception\users\InvalidDateDayException.php';


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
}