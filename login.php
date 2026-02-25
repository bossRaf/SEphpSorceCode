
<?php
session_start();
include "function.php";

// If already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $user = loginUser($username, $password);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['message'] = "Welcome, " . htmlspecialchars($user['username']) . "!";
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}

include "templates/header.php";
?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow p-4" style="width: 400px;">
        <h4 class="text-center mb-3">Login</h4>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label fw-bold">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">
                Login
            </button>
        </form>

        <div class="text-center">
            <small>
                Don’t have an account yet? 
                <a href="register.php">Register here</a>
            </small>
        </div>

        <div class="text-center mt-2">
            <small>
                <a href="#">Forgot Password?</a>
            </small>
        </div>
    </div>
</div>

<?php include "templates/footer.php"; ?>







