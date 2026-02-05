<?php
require_once "function.php";
session_start();

// Display the message if it exists in the session
if (isset($_SESSION['message'])) {
    // Display the message above the header without blocking it
    echo '<div class="alert alert-info text-left w-100" role="alert" style="margin: 0; background-color: white;">' . $_SESSION['message'] . '</div>';
    unset($_SESSION['message']);  // Clear the message after showing it
}
include_once('templates/header.php'); // Start the session to fetch the message


// Fetch all products to display
$products = displayProductsTable();
?>

<!-- MAIN CONTENT (BODY) -->
<div class="container my-4">
    <h2 class="mb-3">Products</h2>

    <!-- Add New Product Button -->
    <a href="addProduct.php" class="btn btn-primary mb-3">Add New Product</a>

    <?php if (empty($products)): ?>
        <div class="alert alert-warning">No products found.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th> <!-- Keep the ID column header -->
                        <th>Product Name</th>
                        <th>Product Description</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td>
                               <a href="details.php?id=<?php echo htmlspecialchars($product['id']); ?>" class="btn btn-info btn-sm">View</a>
                            </td>
                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($product['product_description']); ?></td>
                            <td><?php echo htmlspecialchars($product['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include_once('templates/footer.php'); ?>


