<?php
require_once 'src/main/domain/model/exception/users/UserNotFoundException.php';
require_once 'src/main/domain/model/exception/users/InsufficientFieldsException.php';
require_once 'src/main/domain/model/exception/users/UserExistsException.php';
require_once 'src/main/domain/model/exception/database/InvalidDbQueryException.php';

class UserRepositoryImpl implements UserRepository {
    private $connector;
    public function __construct() {
        $this->connector = SqlConnector::getInstance();
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
    public function findById($id): User {
        $result = null;
        try {
            $result = $this->connector
                ->execute_query('SELECT * FROM users WHERE user_id = ?', [$id])
                ->fetch_assoc();
        } catch (mysqli_sql_exception $e) {
            throw new InvalidDbQueryException($e);
        }
        if (!$result) throw new UserNotFoundException;
        return new User(
            $result['user_id'],
            $result['name'],
            $result['email'],
            $result['password'],
            $result['birthday'],
        );
    }

    public function findByEmail($email): User {
        $result = null;
        try {
            $result = $this->connector
                ->execute_query('SELECT * FROM users WHERE email = ?', [$email])
                ->fetch_assoc();
        } catch (mysqli_sql_exception $e) {
            throw new InvalidDbQueryException($e);
        }
        if (!$result) throw new UserNotFoundException;
        return new User(
            $result['user_id'],
            $result['name'],
            $result['email'],
            $result['password'],
            $result['birthday'],
        );
    }
    public function update(UserUpdateRequest $user): bool {
        $currentUser = $this->findById($user->getId());
        if (!$currentUser) throw new UserNotFoundException;

        $query = "UPDATE users SET";
        $updates = [];
        $values = [];
        if ($user->getName() && $user->getName() !== $currentUser->getName()) {
            $updates[] = " name = ?"; 
            $values[] = $user->getName();
        }
        if ($user->getEmail() && $user->getEmail() !== $currentUser->getEmail()) {
            $updates[] = " email = ?"; 
            $values[] = $user->getEmail();
        }
        if ($user->getPassword() && !password_verify($user->getPassword(), $currentUser->getPassword())) {
            $updates[] = " password = ?"; 
            $values[] = password_hash($user->getPassword(), PASSWORD_BCRYPT);
        }
        if ($user->getBirthday() && $user->getBirthday() !== $currentUser->getBirthday()) {
            $updates[] = " birthday = ?"; 
            $values[] = $user->getBirthday();
        }
        if (!empty($updates)) $query .= implode(',', $updates); else throw new InsufficientFieldsException;
        $query .= " WHERE user_id = ?";
        $values[] = $user->getId();
        $this->connector->execute_query($query, $values);
        if ($this->connector->affected_rows > 0) return true; return false; 
    }
    public function delete(int $id): bool{
        try {
            $this->connector
                ->execute_query('DELETE FROM users WHERE user_id = ?', [$id]);
            if ($this->connector->affected_rows > 0) return true; else throw new UserNotFoundException;
        } catch (mysqli_sql_exception $e) {
            throw new InvalidDbQueryException($e);
        }
    }
}