
<?php
include "function.php";
session_start();
include "templates/header.php";

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = "You must be logged in to update a product.";
    header("Location: login.php");
    exit();
}

// Check for product ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['message'] = "Invalid product ID.";
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
$product = getProductById($id);

if (!$product) {
    $_SESSION['message'] = "Product not found.";
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productName = $_POST['product_name'];
    $productDescription = $_POST['product_description'];

    // Handle image upload
    $imageName = $product['product_image']; // keep existing if no new upload
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === 0) {
        $targetDir = "img/";
        $imageName = time() . "_" . basename($_FILES["product_image"]["name"]);
        $targetFile = $targetDir . $imageName;
        move_uploaded_file($_FILES["product_image"]["tmp_name"], $targetFile);
    }

    $updatedBy = $_SESSION['user_id'];

    $updatedId = updateProductWithImage($id, $productName, $productDescription, $imageName, $updatedBy);

    if ($updatedId) {
        header("Location: details.php?id=" . $updatedId);
        exit();
    } else {
        $_SESSION['message'] = "Failed to update product.";
        header("Location: index.php");
        exit();
    }
}
?>

<div class="container my-4">
    <h2 class="mb-3">Update Product Form</h2>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="product_name" class="form-label fw-bold">Product Name</label>
            <input type="text"
                   class="form-control"
                   id="product_name"
                   name="product_name"
                   placeholder="Specify Product Name"
                   value="<?php echo htmlspecialchars($product['product_name']); ?>"
                   required
                   minlength="3">
        </div>

        <div class="mb-3">
            <label for="product_description" class="form-label fw-bold">Product Description</label>
            <textarea class="form-control"
                      id="product_description"
                      name="product_description"
                      placeholder="Specify Product Description"
                      rows="8"
                      required><?php echo htmlspecialchars($product['product_description']); ?></textarea>
        </div>

        <div class="mb-3">
            <label for="product_image" class="form-label fw-bold">Product Image</label>
            <input type="file"
                   class="form-control"
                   id="product_image"
                   name="product_image"
                   accept="image/*">
            <?php if (!empty($product['product_image'])): ?>
                <small class="text-muted">Current Image: <?php echo htmlspecialchars($product['product_image']); ?></small>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary px-4 py-2">Update</button>
    </form>
</div>

<?php include "templates/footer.php"; ?>






