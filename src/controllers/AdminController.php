<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Users.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Orders.php';

class AdminController {
    private $userModel;
    private $productModel;
    private $orderModel;
    private $db;

    public function __construct() {
        $this->userModel = new User();
        $this->productModel = new Product();
        $this->orderModel = new Order();
        $this->db = new Database();
    }

    public function getDashboardMetrics() {
        // Get total sales
        $salesStmt = $this->db->query("
            SELECT SUM(total_amount) as total_sales 
            FROM orders 
            WHERE status = 'completed'
        ");
        $totalSales = $salesStmt->fetchColumn();
        if ($totalSales === null) {
            $totalSales = 0.0;
        }

        // Get total customers
        $customersStmt = $this->db->query("
            SELECT COUNT(*) as total_customers 
            FROM users 
            WHERE role = 'customer'
        ");
        $totalCustomers = $customersStmt->fetchColumn();

        // Get total products
        $productsStmt = $this->db->query("
            SELECT COUNT(*) as total_products 
            FROM products
        ");
        $totalProducts = $productsStmt->fetchColumn();

        // Get recent orders
        $recentOrdersStmt = $this->db->query("
            SELECT o.*, u.username 
            FROM orders o
            JOIN users u ON o.user_id = u.id
            ORDER BY o.created_at DESC
            LIMIT 5
        ");
        $recentOrders = $recentOrdersStmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'total_sales' => $totalSales,
            'total_customers' => $totalCustomers,
            'total_products' => $totalProducts,
            'recent_orders' => $recentOrders
        ];
    }

    // In AdminController.php
    public function getAllCustomers() {
        $stmt = $this->db->query("
            SELECT id, username, email, created_at 
            FROM users 
            WHERE role = 'customer'
            ORDER BY created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllProducts() {
        $stmt = $this->db->query("SELECT * FROM products ORDER BY name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllOrders($status = null) {
        $sql = "
            SELECT o.*, u.username 
            FROM orders o
            JOIN users u ON o.user_id = u.id
        ";
        if ($status) {
            $sql .= " WHERE o.status = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$status]);
        } else {
            // Exclude 'cart' orders by default
            $sql .= " WHERE o.status != 'cart'";
            $stmt = $this->db->query($sql);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createProduct($data) {
        // Basic validation
        if (empty($data['name']) || empty($data['price']) || empty($data['stock_quantity'])) {
            return false;
        }
        return $this->productModel->createProduct($data);
    }

    public function updateProduct($id, $data) {
        if (empty($id) || empty($data['name']) || empty($data['price']) || empty($data['stock_quantity'])) {
            return false;
        }
        return $this->productModel->updateProduct($id, $data);
    }

    public function deleteProduct($id) {
        if (empty($id)) {
            return false;
        }
        return $this->productModel->deleteProduct($id);
    }

    public function getProductById($id) {
        if (empty($id)) {
            return null;
        }
        return $this->productModel->getProductById($id);
    }

    public function updateOrderStatus($orderId, $status) {
        if (empty($orderId) || empty($status)) {
            return false;
        }
        return $this->orderModel->updateOrderStatus($orderId, $status);
    }
}
?>