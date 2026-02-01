<?php
/**
 * ORM для навчального проєкту
 * Працює через PDO
 */
class ORM {
    protected static $table;        // Таблиця
    protected static $primaryKey;   // Primary key або масив
    protected $pdo;
    protected $attributes = [];
    protected $wheres = [];

    public function __construct($pdo, $attributes = []) {
        $this->pdo = $pdo;
        $this->attributes = $attributes;
        $this->wheres = [];
    }

    public function __get($key) {
        return $this->attributes[$key] ?? null;
    }

    public function __set($key, $value) {
        $this->attributes[$key] = $value;

        if(property_exists($this, $key)) {
        $this->$key = $value;
        }
    }

    public function where($column, $value) {
        $this->wheres[] = [$column, $value];
        return $this;
    }

    public function get() {
        $table = static::$table;
        $sql = "SELECT * FROM $table";
        $params = [];

        if (!empty($this->wheres)) {
            $conditions = [];
            foreach ($this->wheres as $i => $w) {
                $conditions[] = "{$w[0]} = :param$i";
                $params[":param$i"] = $w[1];
            }
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[] = new static($this->pdo, $row);
        }
        return $results;
    }

    public function first() {
        $results = $this->get();
        return $results[0] ?? null;
    }

    public function isEmpty() {
        return empty($this->get());
    }

    // ========================
    // Save (Insert або Update)
    // ========================
    public function save() {
        $table = static::$table;
        $pk = static::$primaryKey;

        // Якщо ключ масив
        $isUpdate = false;
        if (is_array($pk)) {
            $isUpdate = true;
            foreach ($pk as $k) {
                if (empty($this->attributes[$k])) {
                    $isUpdate = false;
                    break;
                }
            }
        } else {
            $isUpdate = !empty($this->attributes[$pk]);
        }

        if ($isUpdate) {
            return $this->update(); // 🔹 викликаємо новий метод update
        } else {
            // INSERT
            $fields = array_keys($this->attributes);
            $placeholders = array_map(fn($f) => ":$f", $fields);
            $params = [];
            foreach ($this->attributes as $k => $v) $params[":$k"] = $v;

            $sql = "INSERT INTO $table (" . implode(",", $fields) . ") VALUES (" . implode(",", $placeholders) . ")";
            $stmt = $this->pdo->prepare($sql);
            $res = $stmt->execute($params);

            if (!is_array($pk)) {
                $this->attributes[$pk] = $this->pdo->lastInsertId();
            }
            return $res;
        }
    }

    // ========================
    // Новий метод Update
    // ========================
    public function update() {
        $table = static::$table;
        $pk = static::$primaryKey;

        $params = [];
        $fields = [];

        // Визначаємо умови WHERE
        if (is_array($pk)) {
            $where = [];
            foreach ($pk as $k) {
                if (!isset($this->attributes[$k])) {
                    throw new Exception("Неможливо оновити: не задано ключ $k");
                }
                $where[] = "$k = :$k";
                $params[":$k"] = $this->attributes[$k];
            }
        } else {
            if (!isset($this->attributes[$pk])) {
                throw new Exception("Неможливо оновити: не задано ключ $pk");
            }
            $where = ["$pk = :id"];
            $params[":id"] = $this->attributes[$pk];
        }

        // Формуємо SET для UPDATE
        foreach ($this->attributes as $key => $value) {
            if (is_array($pk) && in_array($key, $pk)) continue;
            if (!is_array($pk) && $key == $pk) continue;
            $fields[] = "$key = :$key";
            $params[":$key"] = $value;
        }

        $sql = "UPDATE $table SET " . implode(", ", $fields) . " WHERE " . implode(" AND ", $where);
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    // ========================
    // Soft delete
    // ========================
    public function delete() {
        $table = static::$table;
        $pk = static::$primaryKey;

        if (is_array($pk)) {
            $where = [];
            $params = [];
            foreach ($pk as $k) {
                $where[] = "$k = :$k";
                $params[":$k"] = $this->attributes[$k];
            }
            $sql = "UPDATE $table SET isDeleted = 1 WHERE " . implode(" AND ", $where);
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        } else {
            $stmt = $this->pdo->prepare("UPDATE $table SET isDeleted = 1 WHERE $pk = :id");
            return $stmt->execute([':id' => $this->attributes[$pk]]);
        }
    }

    public static function all($pdo) {
        $table = static::$table;
        $stmt = $pdo->query("SELECT * FROM $table WHERE isDeleted = 0");
        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[] = new static($pdo, $row);
        }
        return $results;
    }

    public static function find($pdo, $id) {
        $table = static::$table;
        $pk = static::$primaryKey;

        if (is_array($pk)) {
            $where = [];
            $params = [];
            foreach ($pk as $k) {
                if (!isset($id[$k])) return null;
                $where[] = "$k = :$k";
                $params[":$k"] = $id[$k];
            }
            $sql = "SELECT * FROM $table WHERE " . implode(" AND ", $where) . " AND isDeleted = 0";
        } else {
            $sql = "SELECT * FROM $table WHERE $pk = :id AND isDeleted = 0";
            $params = [':id' => $id];
        }

        $stmt = $GLOBALS['pdo']->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new static($GLOBALS['pdo'], $row) : null;
    }
}
