<?php
require_once 'src\main\domain\model\exception\users\InvalidAuthException.php';
require_once 'src\main\domain\model\exception\users\SessionExistsException.php';

class SessionService {
    private UserMapper $userMapper;
    private UserRepository $userRepository;
    public function __construct() {
        $this->userMapper = new UserMapper();
        $this->userRepository = new UserRepositoryImpl();
    }
    public function start($data): void {
        session_start();
        if (isset($_SESSION['user'])) throw new SessionExistsException;
        $userAuth = $this->userMapper->toUserAuthRequest($data);
        try {
            $currentUser = $this->userRepository->findByEmail($userAuth->getEmail());
            if (!password_verify($userAuth->getPassword(), $currentUser->getPassword())) throw new InvalidAuthException;
            $user = $this->userMapper->toUserResponse($currentUser);
            $_SESSION['user'] = $user;
            $_SESSION['authenticated'] = true;
        } catch (UserNotFoundException $e) {
            throw new InvalidAuthException;
        }
    }
    public function retrieve() {
        session_start();
        if (isset($_SESSION['user'])) return $_SESSION['user']; else return;
    }
    public function remove(): bool {
        session_start();
        if (isset($_SESSION['user'])) {
            session_unset();
            session_destroy();
            return true;
        }
        return false;
    }
}