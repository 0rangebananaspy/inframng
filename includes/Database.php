<?php
class Database {
    private static $instance = null;
    private $dataPath;
    private $cache = [];

    private function __construct() {
        $config = require __DIR__ . '/../config/config.php';
        $this->dataPath = $config['data_path'];
        
        if (!file_exists($this->dataPath)) {
            mkdir($this->dataPath, 0755, true);
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function read($table) {
        $file = $this->dataPath . $table . '.json';
        
        if (isset($this->cache[$table])) {
            return $this->cache[$table];
        }

        if (!file_exists($file)) {
            $this->cache[$table] = [];
            return [];
        }

        $content = file_get_contents($file);
        $data = json_decode($content, true);
        $this->cache[$table] = $data ?: [];
        return $this->cache[$table];
    }

    public function write($table, $data) {
        $file = $this->dataPath . $table . '.json';
        $this->cache[$table] = $data;
        return file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function insert($table, $record) {
        $data = $this->read($table);
        $record['id'] = uniqid();
        $record['created_at'] = date('Y-m-d H:i:s');
        $data[] = $record;
        $this->write($table, $data);
        return $record;
    }

    public function update($table, $id, $record) {
        $data = $this->read($table);
        foreach ($data as &$item) {
            if ($item['id'] === $id) {
                $item = array_merge($item, $record);
                $item['updated_at'] = date('Y-m-d H:i:s');
                break;
            }
        }
        $this->write($table, $data);
        return true;
    }

    public function delete($table, $id) {
        $data = $this->read($table);
        $data = array_filter($data, function($item) use ($id) {
            return $item['id'] !== $id;
        });
        $this->write($table, array_values($data));
        return true;
    }

    public function find($table, $id) {
        $data = $this->read($table);
        foreach ($data as $item) {
            if ($item['id'] === $id) {
                return $item;
            }
        }
        return null;
    }

    public function clearCache($table = null) {
        if ($table) {
            unset($this->cache[$table]);
        } else {
            $this->cache = [];
        }
    }
}
