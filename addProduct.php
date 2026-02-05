<?php
require_once "function.php";
include_once('templates/header.php');

// Start session to store messages
session_start();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $productName = $_POST['product_name'];
    $productDescription = $_POST['product_description'];

    // Call insertProduct function to add the product
    $insertSuccess = insertProduct($productName, $productDescription);

    // Store success or failure message in session
    if ($insertSuccess) {
        $_SESSION['message'] = "New Product data was added successfully!";
    } else {
        $_SESSION['message'] = "Failed to add product.";
    }

    // Redirect to index.php after form submission
    header("Location: index.php");
    exit();
}
?>

<!-- MAIN CONTENT (BODY) -->
<div class="container my-4">
    <h2 class="mb-3">Add New Product Form</h2>

    <form method="POST" action="addProduct.php">
        <div class="mb-3">
            <label for="product_name" class="form-label">Product Name</label>
            <input type="text"
                   class="form-control"
                   id="product_name"
                   name="product_name"
                   placeholder="Specify Product Name"
                   required
                   minlength="3">
        </div>

        <div class="mb-3">
            <label for="product_description" class="form-label">Product Description</label>
            <textarea class="form-control"
                      id="product_description"
                      name="product_description"
                      placeholder="Specify Product Description"
                      rows="6"
                      required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>

<?php include_once('templates/footer.php'); ?>




