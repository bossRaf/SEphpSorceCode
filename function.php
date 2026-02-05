<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function connect() {
    $host = "sql109.infinityfree.com";      // Your host name
    $dbname = "if0_40979905_productsystem";    // Your database name
    $username = "if0_40979905";  // Your username
    $password = "5sQIhS0RsXB";  // Your password

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



// CREATE
function insertProduct($productName, $productDescription) {
    try {
        $pdo = connect();  // Get PDO connection from connect()

        // Prepare SQL statement to insert new product
        $sql = "INSERT INTO products (product_name, product_description)
                VALUES (:productName, :productDescription)";

        // Prepare and bind parameters to prevent SQL injection
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':productName', $productName);
        $stmt->bindParam(':productDescription', $productDescription);

        // Execute the statement
        $stmt->execute();

        // Return true on success
        return true;
    } catch (PDOException $e) {
        // Return false on failure and log the error
        return false;
    } finally {
        $pdo = null;  // Close the PDO connection
    }
}



//insertProduct("tinapay");




// READ PRODUCT BY ID
function displayProductById($id) {
    try {
        $pdo = connect();

        $sql = "SELECT * FROM products WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            echo "Product not found.";
            return;
        }

        echo "<table class='products-table'>";
        echo "<thead><tr>";

        foreach (array_keys($product) as $column) {
            echo "<th>" . htmlspecialchars($column) . "</th>";
        }

        echo "</tr></thead><tbody><tr>";

        foreach ($product as $value) {
            echo "<td>" . htmlspecialchars($value) . "</td>";
        }

        echo "</tr></tbody></table>";

    } catch (PDOException $e) {
        echo "Read failed: " . $e->getMessage();
    }
}

 //displayProductById(1);




// UPDATE PRODUCT
function editProduct($id, $productName) {
    try {
        $pdo = connect();

        $sql = "UPDATE products
                SET product_name = :product_name
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':product_name', $productName);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        echo "Update successful";
    } catch (PDOException $e) {
        echo "Update failed: " . $e->getMessage();
    }
}

 //editProduct(17, "Mango");




// DELETE PRODUCT
function deleteProduct($id) {
    try {
        $pdo = connect();

        $sql = "DELETE FROM products WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        echo "Delete successful";
    } catch (PDOException $e) {
        echo "Delete failed: " . $e->getMessage();
    }
}

 //deleteProduct(17);

function getProductById($id) {
   try {
        $pdo = connect();

        $sql = "SELECT * FROM products WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return false;
    }
}


// READ ALL PRODUCTS
function displayProductsTable() {
    try {
        $pdo = connect();
        $stmt = $pdo->query("SELECT * FROM products ORDER BY id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Display failed: {$e->getMessage()}</div>";
        return [];
    }
}





