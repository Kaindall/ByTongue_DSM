<?php
require_once 'src/main/infrastructure/config/Logger.php';
require_once 'src/main/domain/model/exception/chats/ChatNotFoundException.php';
require_once 'src/main/domain/model/exception/chats/InvalidChatObjectException.php';
require_once 'src/main/domain/model/exception/chats/ChatAlreadyExistException.php';
require_once 'src/main/domain/model/exception/chats/InvalidIdentifierException.php';
require_once 'src/main/domain/model/exception/chats/UnexpectedChatCreationException.php';
require_once 'src/main/domain/model/exception/chats/UnexpectedChatUpdateException.php';
require_once 'src/main/domain/model/exception/chats/UnexpectedChatDeletionException.php';
use MongoDB\Driver\Manager;
use MongoDB\Driver\Command;
use MongoDB\Driver\BulkWrite;
use MongoDB\BSON\ObjectId;

class MongoChatRepositoryImpl implements ChatRepository {

    private Manager $connector;

    public function __construct() {
        $this->connector = MongoConnector::getInstance();
    }
    public function findById($id): Chat {
        $objectId = null;
        try {
            $objectId = new ObjectId($id);
        } catch (InvalidArgumentException $e) {throw new InvalidIdentifierException;}
        $command = new Command([
            'find' => 'chats',
            'filter' => ['_id' => $objectId]
        ]);
        $results = $this->connector->executeCommand('meetings', $command);
        Logger::info($results);
        $result = false;
        
        foreach($results as $document) {
            $result = json_decode(json_encode($document), true);
        }
        if (!$result) throw new ChatNotFoundException($id);
        LoggeR::info($result);
        return new Chat(
            $id, 
            $result['model'], 
            $result['contents'],
            $result['from'],
            $result['target'],
            $result['level']
        );
    }
    public function create(Chat $chat): Chat {
        if (!($chat instanceof Chat)) throw new InvalidChatObjectException;
        $id = new ObjectId();
        $writer = new BulkWrite();
        
        $chat->setId($id->__toString());
        $writer->insert([
            '_id' => $id,
            'model' => $chat->getModel(),
            'from' => $chat->getOrigin(),
            'target' => $chat->getTarget(),
            'level'=> $chat->getLevel(),
            'contents' => $chat->getContents()
        ]);

        $result = $this->connector->executeBulkWrite('meetings.chats', $writer);
        if ($result->getInsertedCount() > 0) return $chat; else throw new UnexpectedChatCreationException;
    }
    public function update(Chat $chat, array $newContent): bool {
        $id = new ObjectId($chat->getId());
        $writer = new BulkWrite();

        $writer->update(
            ['_id' => $id],
            ['$push' => ['contents' => ['$each' => $newContent]]],
            ['upsert' => false]
        );
        
        $result = $this->connector->executeBulkWrite('meetings.chats', $writer);
        if ($result->getModifiedCount() > 0) return true; else throw new UnexpectedChatUpdateException;
    }
    public function delete($id): bool{
        $writer = new BulkWrite();
        $writer->delete(
            ['_id' => new ObjectId($id)],
            ['limit' => 1]
        );
        $result = $this->connector->executeBulkWrite('meetings.chats', $writer);
        if ($result->getDeletedCount() > 0) return true; else throw new UnexpectedChatDeletionException;
    }

    public function deleteMessage(string $chatId, string $msgId): bool {
        $writer = new BulkWrite();
        $writer->update(
            ['_id' => new ObjectId($chatId)],
            ['$pull' => ['contents' => ['id' => $msgId]]],
            ['multi' => false]
        );
        $result = $this->connector->executeBulkWrite('meetings.chats', $writer);
        if ($result->getModifiedCount() > 0) return true; else throw new UnexpectedChatDeletionException;
    }
}