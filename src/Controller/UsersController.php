<?php
namespace Src\Controller;

use Src\TableGateways\UsersGateway;

class UsersController {
    private $db;
    private $requestMethod;
    private $id;
    private $usersGateway;

    public function __construct($db, $requestMethod, $id) {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->id = $id;
        $this->usersGateway = new UsersGateway($db);
    }

    public function processRequest() {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->id) {
                    $response = $this->getById($this->id);
                } else {
                    $response = $this->getAll();
                };
                break;
            case 'POST':
                $response = $this->createRequest();
                break;
            case 'PUT':
                $response = $this->updateFromRequest($this->id);
                break;
            case 'DELETE':
                $response = $this->deleteById($this->id);
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }

        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function getAll() {
        $result = $this->usersGateway->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        header("Content-Type: application/json; charset=UTF-8");
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getById($id) {
        $result = $this->usersGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function createRequest() {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        if (! $this->validateCreate($input)) {
            return $this->unprocessableEntityResponse();
        }

        $passwordHash = password_hash($input['password'], PASSWORD_DEFAULT);

        $this->usersGateway->insert([
            'email' => $input['email'],
            'password_hash' => $passwordHash
        ]);

        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = null;
        return $response;
    }

    private function updateFromRequest($id) {
        $result = $this->usersGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }

        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        if (! $this->validateUpdate($input)) {
            return $this->unprocessableEntityResponse();
        }

        if (isset($input['password'])) {
            $input['password_hash'] = password_hash($input['password'], PASSWORD_DEFAULT);
        }

        $this->usersGateway->update($id, $input);

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function deleteById($id) {
        $result = $this->usersGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }

        $this->usersGateway->delete($id);

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function validateCreate($input) {
        if (! isset($input['email'])) {
            return false;
        }
        if (! isset($input['password'])) {
            return false;
        }
        return true;
    }

    private function validateUpdate($input) {
        if (! isset($input['email'])) {
            return false;
        }
        return true;
    }

    private function unprocessableEntityResponse() {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
        return $response;
    }

    private function notFoundResponse() {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}