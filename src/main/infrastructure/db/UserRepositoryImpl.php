<?php
require_once 'src\main\domain\model\exception\users\UserNotFoundException.php';
require_once 'src\main\domain\model\exception\users\UserExistsException.php';
require_once 'src\main\domain\model\exception\database\InvalidDbQueryException.php';

class UserRepositoryImpl implements UserRepository {
    private $connector;
    public function __construct() {
        $this->connector = DbConnector::getInstance();
    }
    public function create(User $user): int {
        try {
            $this->connector
                    ->execute_query(
                        'INSERT INTO users (name, email, password, birthday) VALUES (?, ?, ?, ?)',
                        [$user->getName(), $user->getEmail(), password_hash($user->getPassword(), PASSWORD_BCRYPT), $user->getBirthday()]);
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() === 1062) throw new UserExistsException;
            throw new InvalidDbQueryException($e);
        }
        return $this->connector->insert_id;
    }
    public function findById($id): UserResponseDTO {
        $result = null;
        try {
            $result = $this->connector
                ->execute_query('SELECT user_id, name, email, birthday FROM users WHERE user_id = ?', [$id])
                ->fetch_assoc();
        } catch (mysqli_sql_exception $e) {
            throw new InvalidDbQueryException($e);
        }
        if (!$result) throw new UserNotFoundException;
        return new UserResponseDTO(
            $result['user_id'],
            $result['name'],
            $result['email'],
            $result['birthday'],
        );
    }
    public function update() {
        return PHP_EOL . 'atualizar usuário';
    }
    public function delete(){
        return PHP_EOL . 'remover usuário';
    }
}