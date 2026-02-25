<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function connect() {
    $host = ""; 
    $dbname = ""; 
    $username = ""; 
    $password = "";  
	
    try {
        $conn = new PDO(
            "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
            $username,
            $password
        );
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

// LOGIN: Verify user credentials
function loginUser($username, $password) {
    try {
        $pdo = connect();
        $sql = "SELECT * FROM userAccount WHERE username = :username LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            
            if ($user['userpassword'] === $password) {
                return $user;
            } else {
                return false; 
            }
        } else {
            return false; 
        }

    } catch (PDOException $e) {
        return false;
    } finally {
        $pdo = null;
    }
}


// READ: Load all products with user info
function loadProducts() {
    try {
        $pdo = connect();
        
        $sql = "SELECT 
                    p.id,
                    p.product_name,
                    p.product_description,
                    p.created_at,
                    p.updated_at,
      				p.product_image,
                    ua.username AS added_by_username,
                    ub.username AS updated_by_username
                FROM products p
                LEFT JOIN userAccount ua ON p.added_by = ua.id
                LEFT JOIN userAccount ub ON p.updated_by = ub.id
                ORDER BY p.id ASC";

        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Display failed: {$e->getMessage()}</div>";
        return [];
    }
}

// CREATE: Insert a product
function insertProduct($productName, $productDescription, $productImage, $addedBy) {
    try {
        $pdo = connect(); 

        $sql = "INSERT INTO products 
                    (product_name, product_description, product_image, added_by) 
                VALUES 
                    (:productName, :productDescription, :productImage, :addedBy)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':productName', $productName);
        $stmt->bindParam(':productDescription', $productDescription);
        $stmt->bindParam(':productImage', $productImage);
        $stmt->bindParam(':addedBy', $addedBy, PDO::PARAM_INT);
       
        // Execute the query
        $stmt->execute();
        
        // Return the inserted product ID immediately after execution
        return $pdo->lastInsertId(); // Fetch and return the last inserted ID
        
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    } finally {
        $pdo = null;
    }
}




// Get total number of products
function getTotalProducts() {
    try {
        $pdo = connect();
        $sql = "SELECT COUNT(*) as total FROM products";
        $stmt = $pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    } catch (PDOException $e) {
        return 0;
    } finally {
        $pdo = null;
    }
}


// REGISTER: Create a new user
function registerUser($username, $password) {
    try {
        $pdo = connect();

        // Insert new user
        $sql = "INSERT INTO userAccount (username, userpassword) VALUES (:username, :password)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password); // store plain or hashed
        $stmt->execute();

        return true;

    } catch (PDOException $e) {
        return false; // username exists or other error
    } finally {
        $pdo = null;
    }
}

// VIEW product by id
function getProductById($id) {
   try {
        $pdo = connect();

        $sql = "SELECT 
                    p.id,
                    p.product_name,
                    p.product_description,
                    p.created_at,
                    p.updated_at,
                    p.product_image,
                    p.added_by,
                    p.updated_by,
                    ua.username AS added_by_username,
                    ub.username AS updated_by_username
                FROM products p
                LEFT JOIN userAccount ua ON p.added_by = ua.id
                LEFT JOIN userAccount ub ON p.updated_by = ub.id
                WHERE p.id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        return false;
    }
}



// Update Products 
function updateProductWithImage($id, $productName, $productDescription, $productImage, $updatedBy) {
    try {
        $pdo = connect();

        $sql = "UPDATE products 
                SET product_name = :productName,
                    product_description = :productDescription,
                    product_image = :productImage,
                    updated_by = :updatedBy
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':productName', $productName);
        $stmt->bindParam(':productDescription', $productDescription);
        $stmt->bindParam(':productImage', $productImage);
        $stmt->bindParam(':updatedBy', $updatedBy, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        // Return the ID for redirecting to details.php
        return $id;

    } catch (PDOException $e) {
        return false;
    } finally {
        $pdo = null;
    }
}








// Update User Account
function updateUserProfile($userId, $firstName, $lastName) {
    try {
        $pdo = connect();

        $sql = "UPDATE userAccount 
                SET first_name = :firstName, 
                    last_name = :lastName
                WHERE id = :userId";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

        $stmt->execute();

        return true; // success

    } catch (PDOException $e) {
        return false; // failed
    } finally {
        $pdo = null;
    }
}





// Search Product by name
function searchProductsByName($searchTerm) {
    try {
        $pdo = connect();
        $sql = "SELECT 
                    p.id, 
                    p.product_name, 
                    p.created_at 
                FROM products p
                WHERE p.product_name LIKE :search
                ORDER BY p.id ASC";
        $stmt = $pdo->prepare($sql);
        $likeTerm = "%" . $searchTerm . "%";
        $stmt->bindParam(':search', $likeTerm);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    } finally {
        $pdo = null;
    }
}



// Delete Products 
function deleteProduct($id) {
    try {
        $pdo = connect();

        $sql = "DELETE FROM products WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        return false;
    } finally {
        $pdo = null;
    }
}






