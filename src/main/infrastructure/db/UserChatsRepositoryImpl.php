<?php 
require_once 'src/main/domain/model/exception/users/UserExistsException.php';
require_once 'src/main/domain/model/exception/users/LinkNotFoundException.php';
require_once 'src/main/domain/model/exception/database/InvalidDbQueryException.php';

class UserChatsRepositoryImpl implements UserChatsRepository {
    private $connector;
    public function __construct() {
        $this->connector = SqlConnector::getInstance();
    }

    public function findChats(int $userId): array {
        $results = [];
        try {
            $result = $this->connector
                ->execute_query('SELECT * FROM users_chats WHERE user_id = ?', [$userId]);
        } catch (mysqli_sql_exception $e) {
            throw new InvalidDbQueryException($e);
        }
        if (empty($result) || !$result) throw new LinkNotFoundException;
        foreach ($result as $row) {
            $results[] = $row;
        }
        return $results;
    }
    public function createLink(int $userId, string $chatId): bool {
        try {
            $this->connector
                    ->execute_query(
                        'INSERT INTO users_chats (user_id, chat_id, created_dt, updt_dt) VALUES (?, ?, ?, ?)',
                        [$userId, $chatId, date('Y-m-d'), date('Y-m-d')]);
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() === 1062) throw new UserExistsException;
            throw new InvalidDbQueryException($e);
        }
        return true;
    }

    public function refreshLink(string $chatId): bool {
        try {
            $this->connector
                ->execute_query('UPDATE users_chats SET updt_dt = ? WHERE chat_id = ?', [date('Y-m-d'), $chatId]);
            if ($this->connector->affected_rows > 0) return true; else throw new LinkNotFoundException;
        } catch (mysqli_sql_exception $e) {
            throw new InvalidDbQueryException($e);
        }
    }

    public function deleteLink(string $chatId): bool {
        try {
            $this->connector
                ->execute_query('DELETE FROM users_chats WHERE chat_id = ?', [$chatId]);
            if ($this->connector->affected_rows > 0) return true; else throw new LinkNotFoundException;
        } catch (mysqli_sql_exception $e) {
            throw new InvalidDbQueryException($e);
        }
    }

    public function deleteAllLinks(string $userId): bool {
        try {
            $this->connector
                ->execute_query('DELETE FROM users_chats WHERE user_id = ?', [$userId]);
            if ($this->connector->affected_rows > 0) return true; else throw new LinkNotFoundException;
        } catch (mysqli_sql_exception $e) {
            throw new InvalidDbQueryException($e);
        }
    }
}
