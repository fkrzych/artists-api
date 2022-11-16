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

        $stmt->bindValue(':firstName', $artistData[0]['name']);
        $stmt->bindValue(':surname', $artistData[0]['surname']);
        $stmt->bindValue(':painting', $artistData[0]['painting']);

        $stmt->execute();
    }

    public function updateArtist(string $id, array $artistData): int
    {
        $fields = [];

        if (!empty($artistData[0]['name'])) {
            $fields['name'] = [
                $artistData[0]['name'],
                PDO::PARAM_STR
            ];
        }

        if (!empty($artistData[0]['surname'])) {
            $fields['surname'] = [
                $artistData[0]['surname'],
                PDO::PARAM_STR
            ];
        }

        if (!empty($artistData[0]['painting'])) {
            $fields['painting'] = [
                $artistData[0]['painting'],
                PDO::PARAM_STR
            ];
        }

        if (empty($fields)) {
            return 0;
        } else {
            $sets = array_map(function ($value) {
                return "$value = :$value";
            }, array_keys($fields));

            $sql = 'update artist' . ' set ' . implode(', ', $sets) . ' where id = :id';

            $stmt = $this->conn->prepare($sql);

            $stmt->bindValue(":id", $id, PDO::PARAM_INT);

            foreach ($fields as $name => $values) {
                $stmt->bindValue(":$name", $values[0], $values[1]);
            }
            $stmt->execute();

            return $stmt->rowCount();
        }
    }

    public function deleteArtist(string $id): int
    {
        $sql = 'delete from artist where id = :id';

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }
}