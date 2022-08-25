<?php

class ArtistGateway
{
    private PDO $conn;

    public function __construct(private Database $db)
    {
        $this->conn = $db->getConnection();
    }

    public function getAllArtists(): array
    {
        $sql = "select * from artist";

        $stmt = $this->conn->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOneArtist(string $id)
    {
        $sql = 'select * from artist where id = :id';

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertArtist(array $artistData): void
    {
        $sql = 'insert into artist(name, surname, painting) values (:firstName, :surname, :painting)';

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':firstName', $artistData['name']);
        $stmt->bindValue(':surname', $artistData['surname']);
        $stmt->bindValue(':painting', $artistData['painting']);

        $stmt->execute();
    }
}