<?php
require_once "function.php";
session_start();
include_once('templates/header.php');

// Check if ID exists in URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<div class='container my-4 alert alert-danger'>Invalid product ID.</div>";
    include_once('templates/footer.php');
    exit();
}

$id = $_GET['id'];

// Fetch product by ID
$product = getProductById($_GET['id']);

// If product not found
if (!$product) {
    echo "<div class='container my-4 alert alert-warning'>Product not found.</div>";
    include_once('templates/footer.php');
    exit();
}
?>

<!-- MAIN CONTENT -->
<div class="container my-4">

    <!-- Product Name (Top Left) -->
    <h2 class="mb-4">
        <?php echo htmlspecialchars($product['product_name']); ?>
    </h2>

    <!-- Product Details Table -->
    <div class="table-responsive">
        <table class="table table-bordered w-75">
            <tbody>
                <tr>
                    <th style="width: 30%;">Description</th>
                    <td><?php echo htmlspecialchars($product['product_description']); ?></td>
                </tr>
                <tr>
                    <th>Created At</th>
                    <td><?php echo htmlspecialchars($product['created_at']); ?></td>
                </tr>
                <tr>
                    <th>Updated At</th>
                    <td></td> <!-- Blank for future use -->
                </tr>
            </tbody>
        </table>
    </div>

</div>

<?php include_once('templates/footer.php'); ?>



