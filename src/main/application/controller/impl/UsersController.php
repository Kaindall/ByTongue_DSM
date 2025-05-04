<?php

#[HttpController("/users")]
class UsersController implements Controller {
    private UserService $userService;

    public function __construct() {
        $this->userService = new UserService(new UserMapper(), new UserRepositoryImpl(), new UserChatsRepositoryImpl());
    }

    #[HttpEndpoint(uri: "/{id}", method: "GET")]
    #[Authenticated]
    public function find(HttpRequest $request): ExceptionModel|UserResponse {
        header("Content-Type: application/json");
        try {
            $response = $this->userService->findById($request->getPathParams()['id']);
            http_response_code(200);
            return $response;
        } catch (InvalidDbQueryException | InvalidDbConnectionException $e) {
            http_response_code(500);
            return $e;
        } catch (UserNotFoundException $e) {
            http_response_code(404);
            return $e;
        } catch (UnauthorizedAccessException) {
            http_response_code(401);
        }
    }

    #[HttpEndpoint(uri: "/{id}/chats", method: "GET")]
    #[Authenticated]
    public function findChats(HttpRequest $request) {
        header("Content-Type: application/json");
        try {
            $response = $this->userService->findChats($request->getPathParams()['id']);
            http_response_code(200);
            return $response;
        } catch (InvalidDbQueryException | InvalidDbConnectionException $e) {
            http_response_code(500);
            return $e;
        } catch (UserNotFoundException $e) {
            http_response_code(404);
            return $e;
        } catch (UnauthorizedAccessException) {
            http_response_code(401);
        }
    }

    #[HttpEndpoint(uri: "", method: "POST")]
    public function create(HttpRequest $request): ExceptionModel|int {
        header("Content-Type: application/json");
        try {
            $response = $this->userService->create(json_decode($request->getBody(), true));
            http_response_code(201);
            return $response;
        } catch (InvalidDbQueryException | InvalidDbConnectionException $e) {
            http_response_code(500);
            return $e;
        } catch (EmptyNameException | EmptyEmailException | EmptyPasswordException | UserExistsException $e) {
            http_response_code(400);
            return $e;
        }
    }

    #[HttpEndpoint(uri: "/{id}", method: "PUT")]
    #[Authenticated]
    public function update(HttpRequest $request) {
        header("Content-Type: application/json");
        try {
            $this->userService->update($request->getPathParams()['id'], $request->getBody());
            http_response_code(204);
            return;
        } catch (InvalidDbQueryException | InvalidDbConnectionException $e) {
            http_response_code(500);
            return $e;
        } catch (UserNotFoundException $e) {    
            http_response_code(404);
            return $e;
        } catch (UnauthorizedAccessException) {
            http_response_code(401);
        } catch (InsufficientFieldsException | InvalidBirthdayFormatException | InvalidDateDayException | InvalidDateMonthException | InvalidDateYearException $e) {
            http_response_code(400);
            return $e;
        } 
    }

    #[HttpEndpoint(uri: "/{id}", method: "DELETE")]
    #[Authenticated]
    public function remove(HttpRequest $request) {
        header("Content-Type: application/json");
        try {
            $this->userService->delete($request->getPathParams()['id']);
            http_response_code(204);
            return;
        } catch (InvalidDbQueryException | InvalidDbConnectionException $e) {
            http_response_code(500);
            return $e;
        } catch (UserNotFoundException $e) {
            http_response_code(404);
            return $e;
        } catch (UnauthorizedAccessException) {
            http_response_code(401);
        }
    }

    public function fallback(HttpRequest $request) {
        echo 'Fallback do UsersController';
        http_response_code(400);
        return;
    }
}