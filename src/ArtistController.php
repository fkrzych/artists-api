<?php

class ArtistController
{
    public function __construct(private ArtistGateway $artistGateway)
    {
    }

    public function processRequest(string $method, ?string $id): void
    {
        if ($id === null) {
            if ($method == 'GET') {
                echo json_encode($this->artistGateway->getAllArtists());
            } elseif ($method == 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);

                $this->artistGateway->insertArtist($data);

                $this->respondCreated();
            } else {
                $this->respondMethodNotAllowed('GET, POST');
            }
        } else {

            $artist = $this->artistGateway->getOneArtist($id);

            if ($artist === false) {
                $this->respondNotFound($id);
                return;
            }

            switch ($method) {
                case 'GET':
                    echo json_encode($artist);
                    break;
                case 'PATCH':
                    $data = json_decode(file_get_contents('php://input'), true);

                    $rows = $this->artistGateway->updateArtist($id, $data);
                    echo json_encode(['message' => 'Artist updated', 'rows' => $rows]);
                    break;
                case 'DELETE':
                    $rows = $this->artistGateway->deleteArtist($id);
                    echo json_encode(['message' => "Artist of id: $id deleted", "rows" => $rows]);
                    break;
                default:
                    $this->respondMethodNotAllowed('GET, PATCH, DELETE');
            }
        }
    }

    private function respondMethodNotAllowed(string $allowed_methods): void
    {
        http_response_code(405);
        header("Allow: $allowed_methods");
    }

    private function respondNotFound(string $id): void
    {
        http_response_code(404);
        echo json_encode(['message' => "The artist of id $id not found"]);
    }

    private function respondCreated(): void
    {
        http_response_code(201);
        echo json_encode(['message' => "Artist added"]);
    }
}