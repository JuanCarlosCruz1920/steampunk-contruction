<?php
require_once __DIR__ . '/../config/database.php';

class Product {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getDb() {
        return $this->db->connect();
    }

    public function getAllProducts() {
        $stmt = $this->db->query("SELECT * FROM products ORDER BY name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductById($id) {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createProduct($data) {
        $stmt = $this->db->prepare("
            INSERT INTO products 
            (name, description, price, quality_rating, image_path, stock_quantity, steampunk_style_level)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        
        return $stmt->execute([
            $data['name'],
            $data['description'],
            $data['price'],
            $data['quality_rating'] ?? 3,
            $data['image_path'] ?? null,
            $data['stock_quantity'],
            $data['steampunk_style_level'] ?? 5
        ]);
    }

    public function updateProduct($id, $data) {
        // Fetch current product data
        $current = $this->getProductById($id);
        if (!$current) return false;

        // Merge new data with current data
        $merged = array_merge($current, $data);

        $stmt = $this->db->prepare("
            UPDATE products SET
            name = ?,
            description = ?,
            price = ?,
            quality_rating = ?,
            image_path = ?,
            stock_quantity = ?,
            steampunk_style_level = ?,
            updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");

        return $stmt->execute([
            $merged['name'],
            $merged['description'],
            $merged['price'],
            $merged['quality_rating'],
            $merged['image_path'],
            $merged['stock_quantity'],
            $merged['steampunk_style_level'],
            $id
        ]);
    }

    public function deleteProduct($id) {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function searchProducts($query) {
        $stmt = $this->db->prepare("
            SELECT * FROM products 
            WHERE name LIKE ? OR description LIKE ?
            ORDER BY name
        ");
        $searchTerm = "%$query%";
        $stmt->execute([$searchTerm, $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>