<?php
class Model {
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];

    public function __construct($db) {
        $this->db = $db;
    }

    public function find($id) {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?";
        return $this->db->query($sql, [$id])->fetch();
    }

    public function all() {
        $sql = "SELECT * FROM {$this->table}";
        return $this->db->query($sql)->fetchAll();
    }

    public function create($data) {
        $filteredData = array_intersect_key($data, array_flip($this->fillable));
        return $this->db->insert($this->table, $filteredData);
    }

    public function update($id, $data) {
        $filteredData = array_intersect_key($data, array_flip($this->fillable));
        return $this->db->update(
            $this->table,
            $filteredData,
            "{$this->primaryKey} = ?",
            [$id]
        );
    }

    public function delete($id) {
        return $this->db->delete(
            $this->table,
            "{$this->primaryKey} = ?",
            [$id]
        );
    }

    public function paginate($page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        
        $countSql = "SELECT COUNT(*) as total FROM {$this->table}";
        $total = $this->db->fetch($countSql)['total'];
        
        $sql = "SELECT * FROM {$this->table} LIMIT ? OFFSET ?";
        $items = $this->db->fetchAll($sql, [$perPage, $offset]);
        
        return [
            'items' => $items,
            'pagination' => Utils::getPagination($total, $page, $perPage)
        ];
    }

    public function where($conditions, $params = []) {
        $whereClause = implode(' AND ', array_map(function($key) {
            return "$key = ?";
        }, array_keys($conditions)));
        
        $sql = "SELECT * FROM {$this->table} WHERE $whereClause";
        return $this->db->fetchAll($sql, array_values($conditions));
    }

    public function search($query, $fields) {
        $searchConditions = array_map(function($field) {
            return "$field LIKE ?";
        }, $fields);
        
        $searchParams = array_fill(0, count($fields), "%$query%");
        $whereClause = implode(' OR ', $searchConditions);
        
        $sql = "SELECT * FROM {$this->table} WHERE $whereClause";
        return $this->db->fetchAll($sql, $searchParams);
    }

    protected function validate($data) {
        return $data;
    }
} 