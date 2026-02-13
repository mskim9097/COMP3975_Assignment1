<?php
namespace Src\Controller;

use Src\TableGateways\ArticlesGateway;

class ArticlesController {
    private $db;
    private $requestMethod;
    private $id;
    private $articlesGateway;

    public function __construct($db, $requestMethod, $id) {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->id = $id;
        $this->articlesGateway = new ArticlesGateway($db);
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
        $result = $this->articlesGateway->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        header("Content-Type: application/json; charset=UTF-8");
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getById($id) {
        $result = $this->articlesGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function createRequest() {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validate($input)) {
            return $this->unprocessableEntityResponse();
        }

        $this->articlesGateway->insert($input);

        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = null;
        return $response;
    }

    private function updateFromRequest($id) {
        $result = $this->articlesGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }

        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validate($input)) {
            return $this->unprocessableEntityResponse();
        }

        $this->articlesGateway->update($id, $input);

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function deleteById($id) {
        $result = $this->articlesGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }

        $this->articlesGateway->delete($id);

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function validate($input) {
        if (! isset($input['title'])) {
            return false;
        }
        if (! isset($input['content'])) {
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