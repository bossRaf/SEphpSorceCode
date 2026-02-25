<?php
include "function.php";
session_start();

// Handle logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset(); 
    session_destroy(); 
    header("Location: index.php");
    exit();
}


// Header and Products
include "templates/header.php"; 

$products = loadProducts();
?>

<div class="container my-4" id="products">
    <h2 class="mb-3">Products</h2>
    
   <div class="d-flex justify-content-between mb-3" style="max-width: 100%;">
    
    	<?php if (isset($_SESSION['user_id'])): ?>
        	<a href="addProduct.php" class="btn fw-bold" style="background-color: lightgreen; border-color: lightgreen;">
            	Add New Product
        	</a>
    	<?php else: ?>
        	<div></div> <!-- empty div to keep spacing -->
    	<?php endif; ?>

    	<div style="max-width: 400px;">
        	<div class="input-group">
            	<input type="text" id="searchInput" class="form-control" placeholder="Search for product name...">
            	<button id="searchBtn" class="btn btn-primary">Search</button>
        	</div>
    	</div>

	</div>

    <?php if (empty($products)): ?>
        <div class="alert alert-warning">No products found.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle" id="productsTable">
                <thead class="table-dark">
                    <tr>
                        <th> </th>
                        <th>Product Name</th>
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
                            <td><?php echo htmlspecialchars($product['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    	<div class="mt-2">
    		<strong>Total Products:</strong> <?php echo getTotalProducts(); ?>
		</div>
    <?php endif; ?>
</div>

<script>
const searchInput = document.getElementById('searchInput');
const searchBtn = document.getElementById('searchBtn');
const rows = document.querySelectorAll('#productsTable tbody tr');
const totalDiv = document.querySelector('#products .mt-2'); // Correct selector inside #products

function updateTable() {
    const input = searchInput.value.toLowerCase();
    let visibleCount = 0;

    rows.forEach(row => {
        const name = row.cells[1].textContent.toLowerCase();
        const match = name.includes(input) || input === '';
        row.style.display = match ? '' : 'none';
        if (match) visibleCount++;
    });

    const totalCount = rows.length;
    totalDiv.innerHTML = `<strong>Total Products:</strong> ${visibleCount} out of ${totalCount}`;

    // Show notice if no matches
    let notice = document.getElementById('noMatchNotice');
    if (visibleCount === 0) {
        if (!notice) {
            notice = document.createElement('div');
            notice.id = 'noMatchNotice';
            notice.className = 'alert alert-warning mt-2';
            notice.textContent = 'No matching products found.';
            totalDiv.after(notice);
        }
    } else {
        if (notice) notice.remove();
    }
}

// Trigger search on button click
searchBtn.addEventListener('click', updateTable);

// Trigger search on Enter key
searchInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        updateTable();
    }
});
</script>

<?php include "templates/footer.php"; ?>









