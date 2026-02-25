<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
    	<a class="navbar-brand" href="index.php">
            <i>Logo</i>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
    
        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav">
    			<li class="nav-item">
        			<a class="nav-link" href="index.php#products">Products</a>
    			</li>

    			<?php if (isset($_SESSION['user_id'])): ?>
        
        		<li class="nav-item">
            		<a class="nav-link" href="profile.php">Profile</a>
        		</li>

        		<li class="nav-item">
            		<a class="nav-link" href="index.php?action=logout">Logout</a>
        		</li>

    			<?php else: ?>

        		<li class="nav-item">
            		<a class="nav-link" href="login.php">Login</a>
        		</li>
    			<?php endif; ?>
			</ul>
        </div>
    </div>
</nav>
    
    
    

