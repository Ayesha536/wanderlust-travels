<?php
// login.php - User Login Page
$page_title = "User Login";
require_once __DIR__ . '/includes/header.php';

$error = '';
$msg = isset($_GET['msg']) ? sanitize($_GET['msg']) : '';

if (is_logged_in()) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Please enter both email address and password.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];

            $redirect = $_SESSION['redirect_url'] ?? 'dashboard.php';
            unset($_SESSION['redirect_url']);
            header("Location: " . $redirect);
            exit();
        } else {
            $error = "Invalid email address or password combination.";
        }
    }
}
?>

<section class="py-5 bg-warm-ivory min-vh-80 d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card bg-cream-card border-gold p-4 p-md-5 shadow-lg">
                    <div class="text-center mb-4">
                        <i class="fa-solid fa-earth-americas text-gold fs-1 mb-2"></i>
                        <h2 class="font-heading fw-bold">Welcome Back</h2>
                        <p class="text-muted small">Access your bookings, saved itineraries, and visa applications.</p>
                    </div>

                    <?php if ($msg === 'login_required'): ?>
                        <div class="alert alert-warning small py-2"><i class="fa-solid fa-triangle-exclamation me-1"></i>Please login to access your account or save items.</div>
                    <?php endif; ?>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger small py-2"><i class="fa-solid fa-circle-exclamation me-1"></i><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>

                    <form method="POST" action="login.php">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-uppercase text-muted">Email Address</label>
                            <input type="email" name="email" class="form-control form-control-luxury" required placeholder="user@wanderlust.com" value="<?php echo htmlspecialchars($_POST['email'] ?? 'user@wanderlust.com'); ?>">
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="form-label small fw-bold text-uppercase text-muted mb-0">Password</label>
                                <a href="#" class="small text-gold text-decoration-none" onclick="alert('Demo password is: user123 (or admin123 for admin account)')">Forgot Password?</a>
                            </div>
                            <input type="password" name="password" class="form-control form-control-luxury mt-1" required placeholder="••••••••" value="user123">
                        </div>

                        <button type="submit" class="btn btn-gold btn-lg w-100 py-3 fw-bold fs-6 mb-3">
                            <i class="fa-solid fa-right-to-bracket me-2"></i>Sign In to Dashboard
                        </button>
                    </form>

                    <!-- Demo Credentials Card -->
                    <div class="p-3 bg-light rounded border border-gold small mb-3">
                        <strong class="text-main d-block mb-1"><i class="fa-solid fa-key text-gold me-1"></i>Demo Login Accounts:</strong>
                        <div class="text-muted">User Account: <strong>user@wanderlust.com</strong> / <strong>user123</strong></div>
                        <div class="text-muted">Admin Panel: <strong>admin@wanderlust.com</strong> / <strong>admin123</strong></div>
                    </div>

                    <div class="text-center text-muted small">
                        Don't have an account? <a href="register.php" class="text-gold fw-bold text-decoration-none">Create an Account</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
