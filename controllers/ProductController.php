<?php
require_once __DIR__ . '/../models/Product.php';

function showHomePage() {
    global $pdo;
    $productModel = new Product($pdo);
    $featuredProducts = $productModel->getFeatured();
    
    // Pass data to view
    $pageTitle = "The Scent - Premium Aromatherapy Products";
    require_once __DIR__ . '/../views/home.php';
}

function showProductList() {
    global $pdo;
    $productModel = new Product($pdo);
    
    // Handle search
    $searchQuery = $_GET['search'] ?? null;
    $category = $_GET['category'] ?? null;
    $sortBy = $_GET['sort'] ?? 'name_asc';
    
    // Get products based on filters
    if ($searchQuery) {
        $products = $productModel->search($searchQuery);
    } elseif ($category) {
        $products = $productModel->getByCategory($category);
    } else {
        $products = $productModel->getAll();
    }
    
    // Apply sorting
    usort($products, function($a, $b) use ($sortBy) {
        switch ($sortBy) {
            case 'price_asc':
                return $a['price'] <=> $b['price'];
            case 'price_desc':
                return $b['price'] <=> $a['price'];
            case 'name_desc':
                return strcasecmp($b['name'], $a['name']);
            case 'name_asc':
            default:
                return strcasecmp($a['name'], $b['name']);
        }
    });
    
    // Get all categories for filter menu
    $categories = $productModel->getAllCategories();
    
    $pageTitle = $searchQuery ? 
        "Search Results for \"" . htmlspecialchars($searchQuery) . "\"" : 
        ($category ? htmlspecialchars($category) . " Products" : "All Products");
    
    require_once __DIR__ . '/../views/products.php';
}

function handleSearch() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?page=products');
        exit;
    }
    
    $query = $_POST['search'] ?? '';
    header('Location: index.php?page=products&search=' . urlencode($query));
    exit;
}

function filterProducts() {
    $category = $_GET['category'] ?? null;
    $minPrice = $_GET['min_price'] ?? null;
    $maxPrice = $_GET['max_price'] ?? null;
    $sortBy = $_GET['sort'] ?? 'name_asc';
    
    global $pdo;
    $productModel = new Product($pdo);
    
    // Build query conditions
    $conditions = [];
    $params = [];
    
    if ($category) {
        $conditions[] = "category = ?";
        $params[] = $category;
    }
    
    if ($minPrice !== null) {
        $conditions[] = "price >= ?";
        $params[] = $minPrice;
    }
    
    if ($maxPrice !== null) {
        $conditions[] = "price <= ?";
        $params[] = $maxPrice;
    }
    
    // Get filtered products
    $products = $productModel->getFiltered($conditions, $params, $sortBy);
    
    // Return JSON response for AJAX requests
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'products' => $products
    ]);
}

function showProduct($id) {
    global $pdo;
    $productModel = new Product($pdo);
    $product = $productModel->getById($id);
    
    if (!$product) {
        http_response_code(404);
        require_once __DIR__ . '/../views/404.php';
        return;
    }
    
    $pageTitle = $product['name'] . " - The Scent";
    require_once __DIR__ . '/../views/product_detail.php';
}

// Admin functions
function createProduct() {
    if (!isAdmin()) {
        http_response_code(403);
        return;
    }
    
    global $pdo;
    $productModel = new Product($pdo);
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'price' => $_POST['price'],
            'category' => $_POST['category'],
            'image_url' => $_POST['image_url'],
            'featured' => isset($_POST['featured']) ? 1 : 0
        ];
        
        if ($productModel->create($data)) {
            header('Location: index.php?page=products');
            exit;
        }
    }
    
    $pageTitle = "Add New Product - Admin";
    require_once __DIR__ . '/../views/admin/product_form.php';
}

function updateProduct($id) {
    if (!isAdmin()) {
        http_response_code(403);
        return;
    }
    
    global $pdo;
    $productModel = new Product($pdo);
    $product = $productModel->getById($id);
    
    if (!$product) {
        http_response_code(404);
        return;
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'price' => $_POST['price'],
            'category' => $_POST['category'],
            'image_url' => $_POST['image_url'],
            'featured' => isset($_POST['featured']) ? 1 : 0
        ];
        
        if ($productModel->update($id, $data)) {
            header('Location: index.php?page=products');
            exit;
        }
    }
    
    $pageTitle = "Edit Product - Admin";
    require_once __DIR__ . '/../views/admin/product_form.php';
}

function deleteProduct($id) {
    if (!isAdmin()) {
        http_response_code(403);
        return;
    }
    
    global $pdo;
    $productModel = new Product($pdo);
    
    if ($productModel->delete($id)) {
        header('Location: index.php?page=products');
        exit;
    }
}