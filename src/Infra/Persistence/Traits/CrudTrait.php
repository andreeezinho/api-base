<?php

namespace App\Infra\Persistence\Traits;

use PDO;

trait CrudTrait {

    public function findAll(array $filters = []){
        $sql = "SELECT * FROM " . $this->model->getTable();

        $params = [];
        $conditions = [];
        
        if (!empty($filters)) {
            $conditions = $this->setWhereClause($filters, $params);
            $sql .= " WHERE " . $conditions;
        }

        $stmt = $this->conn->prepare($sql);

        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data){
        [$fields, $values] = $this->prepareCreateFields($data);

        $strFields = implode(', ', $fields);

        $sql = "INSERT INTO 
                {$this->model->getTable()}
            SET
                {$strFields}
        ";

        $stmt = $this->conn->prepare($sql);

        $this->prepareBindings($stmt, $values);

        return $stmt->execute();
    }

    public function update(array $data, $object){
        if (empty($data) || !$object) {
            return false;
        }

        [$fields, $params] = $this->prepareUpdatingFields($data, $object);

        $strFields = implode(', ', $fields);

        $sql = "UPDATE 
                {$this->model->getTable()} 
            SET
                {$strFields}
            WHERE
                id = :id
        ";

        $stmt = $this->conn->prepare($sql);

        $this->prepareBindings($stmt, $params);

        $stmt->bindValue(':id', $object->id);

        return $stmt->execute();
    }

    public function delete(int $id) : bool {
        $sql = "DELETE FROM 
                {$this->model->getTable()} 
            WHERE 
                id = :id
        ";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    private function setWhereClause(array $criteria, array &$params): string {
        $conditions = [];

        foreach ($criteria as $field => $value) {
            if ($field === 'name') {
                $conditions[] = "$field LIKE ?";
                $params[] = "%$value%";
                continue;
            }

            if ($field === 'email') {
                $conditions[] = "$field LIKE ?";
                $params[] = "%$value%";
                continue;
            }

            $conditions[] = "$field = :$field";
            $params[":$field"] = $value;
        }

        return implode(' AND ', $conditions);
    }

    private function prepareCreateFields($data): array {
        $fields = [];
        $params = [];

        foreach (get_object_vars($data) as $key => $value) {
            if ($value !== null) {
                $fields[] = "{$key} = :{$key}";
                $params[":{$key}"] = $value;
            }
        }

        return [$fields, $params];
    }

    private function prepareUpdateFields(array $data, $object): array {
        $fields = [];
        $params = [];

        foreach ($data as $key => $value) {
            if (property_exists($object, $key)) {
                $fields[] = "{$key} = :{$key}";
                $params[":{$key}"] = $value;
            }
        }

        return [$fields, $params];
    }

    private function prepareBindings($stmt, array $params): void {
        foreach ($params as $param => $value) {
            $stmt->bindValue($param, $value);
        }
    }

}