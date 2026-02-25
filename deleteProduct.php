
<?php
include "function.php";
session_start();

if (!isset($_GET['id'])) {
    $_SESSION['message'] = "No product selected for deletion.";
    header("Location: index.php");
    exit;
}

$id = (int)$_GET['id'];
$product = getProductById($id);

if (!$product) {
    $_SESSION['message'] = "Product not found.";
    header("Location: index.php");
    exit;
}

if (isset($_POST['confirm_delete'])) {
    if (deleteProduct($id)) {
        $_SESSION['message'] = "Product '{$product['product_name']}' deleted successfully!";
    } else {
        $_SESSION['message'] = "Failed to delete product '{$product['product_name']}'.";
    }
    header("Location: index.php");
    exit;
}

include "templates/header.php";
?>

<!-- Bootstrap Modal -->
<div class="modal fade show" id="deleteModal" tabindex="-1" style="display:block; background-color: rgba(0,0,0,0.5);" aria-modal="true" role="dialog">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
    	<h5 class="modal-title">Confirm Deletion</h5>
	  </div>
      <div class="modal-body">
        Are you sure you want to delete the product: <strong><?php echo htmlspecialchars($product['product_name']); ?></strong>?
      </div>
      	<div class="modal-footer">
    		<form method="post">
        		<button type="submit" name="confirm_delete" class="btn btn-danger">Yes</button>
        		<a href="details.php?id=<?php echo $product['id']; ?>" class="btn btn-secondary">Cancel</a>
    		</form>
		</div>
    </div>
  </div>
</div>

<?php include "templates/footer.php"; ?>






