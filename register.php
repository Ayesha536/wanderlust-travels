<?php
// register.php - User Registration Page
$page_title = "Create Account";
require_once __DIR__ . '/includes/header.php';

$error = '';

if (is_logged_in()) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $passport_no = sanitize($_POST['passport_no']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($name) || empty($email) || empty($password)) {
        $error = "Please fill in all required fields.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "An account with this email address already exists.";
        } else {
            $hashed_pass = password_hash($password, PASSWORD_BCRYPT);
            $ins_stmt = $pdo->prepare("INSERT INTO users (name, email, password, phone, passport_no, role) VALUES (?, ?, ?, ?, ?, 'user')");
            $ins_stmt->execute([$name, $email, $hashed_pass, $phone, $passport_no]);

            $new_user_id = $pdo->lastInsertId();
            $_SESSION['user_id'] = $new_user_id;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_role'] = 'user';

            header("Location: dashboard.php?msg=welcome");
            exit();
        }
    }
}
?>

<section class="py-5 bg-warm-ivory min-vh-80 d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card bg-cream-card border-gold p-4 p-md-5 shadow-lg">
                    <div class="text-center mb-4">
                        <i class="fa-solid fa-user-plus text-gold fs-1 mb-2"></i>
                        <h2 class="font-heading fw-bold">Join Wanderlust Travels</h2>
                        <p class="text-muted small">Register to save custom itineraries, manage bookings, and request visas.</p>
                    </div>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger small py-2"><i class="fa-solid fa-circle-exclamation me-1"></i><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>

                    <form method="POST" action="register.php">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase text-muted">Full Name *</label>
                                <input type="text" name="name" class="form-control form-control-luxury" required placeholder="Alexander Vance" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase text-muted">Email Address *</label>
                                <input type="email" name="email" class="form-control form-control-luxury" required placeholder="alexander@example.com" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase text-muted">Phone Number</label>
                                <input type="tel" name="phone" class="form-control form-control-luxury" placeholder="+1 (555) 234-5678" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase text-muted">Passport Number</label>
                                <input type="text" name="passport_no" class="form-control form-control-luxury" placeholder="US123456789" value="<?php echo htmlspecialchars($_POST['passport_no'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase text-muted">Password *</label>
                                <input type="password" name="password" class="form-control form-control-luxury" required placeholder="••••••••">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase text-muted">Confirm Password *</label>
                                <input type="password" name="confirm_password" class="form-control form-control-luxury" required placeholder="••••••••">
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-gold btn-lg w-100 py-3 fw-bold fs-6">
                                    <i class="fa-solid fa-user-check me-2"></i>Create Account
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="text-center text-muted small mt-4">
                        Already have an account? <a href="login.php" class="text-gold fw-bold text-decoration-none">Sign In</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
