
<?php
include "function.php";
session_start();

include "templates/header.php";

// Ensure product ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<div class='container my-4 alert alert-danger'>Invalid product ID.</div>";
    include "templates/footer.php";
    exit();
}



$id = $_GET['id'];
$product = getProductById($id);





if (!$product) {
    echo "<div class='container my-4 alert alert-warning'>Product not found.</div>";
    include "templates/footer.php";
    exit();
}
?>

<!-- Main Content -->
<div class="container my-4">
    <div class="card shadow-sm">
        <div class="card-header table-dark d-flex flex-column align-items-start">
            <h5 class="mb-2 fw-bold">
                <?php echo htmlspecialchars($product['product_name']); ?>
            </h5>

            <!-- Only show buttons if user is logged in -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="d-flex justify-content-start">
                    <a href="updateProduct.php?id=<?php echo htmlspecialchars($product['id']); ?>" class="btn btn-info btn-sm mr-2 d-flex align-items-center">Edit Product</a> &nbsp;
                    <a href="deleteProduct.php?id=<?php echo htmlspecialchars($product['id']); ?>" class="btn btn-info btn-sm d-flex align-items-center" style="background-color: 							red;">Delete Product</a>
                </div>
            <?php endif; ?>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <tbody>
                        <tr>
                            <th class="w-25">Description</th>
                            <td><?php echo htmlspecialchars($product['product_description']); ?></td>
                        </tr>

                        <tr>
                            <th>Added By</th>
                            <td><?php echo htmlspecialchars($product['added_by_username'] ?? 'Unknown'); ?></td>
                        </tr>

                        <tr>
                            <th>Created At</th>
                            <td><?php echo htmlspecialchars($product['created_at']); ?></td>
                        </tr>

                        <tr>
                            <th>Updated By</th>
                            <td><?php echo htmlspecialchars($product['updated_by_username'] ?? ''); ?></td>
                        </tr>

                        <tr>
                            <th>Updated At</th>
                            <td><?php echo htmlspecialchars($product['updated_at'] ?? ''); ?></td>
                        </tr>

                        <tr>
                            <th>Product Image</th>
                            <td>
                                <?php 
                                if (!empty($product['product_image'])) {
                                    echo "<img src='img/" . htmlspecialchars($product['product_image']) . "' class='img-fluid' style='max-width:300px;' alt='Product Image'>";
                                } else {
                                    echo 'No Image';
                                }
                                ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include "templates/footer.php"; ?>






