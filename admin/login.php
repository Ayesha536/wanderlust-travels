<?php
// admin/login.php - Admin Portal Login Page
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth_check.php';

$error = '';

if (is_admin()) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        if ($user['role'] === 'admin') {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = 'admin';

            header("Location: index.php");
            exit();
        } else {
            $error = "Access denied. You do not have administrator permissions.";
        }
    } else {
        $error = "Invalid admin credentials.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal Login - Wanderlust Travels</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-dark-charcoal min-vh-100 d-flex align-items-center justify-content-center">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card bg-cream-card border-gold p-4 p-md-5 shadow-lg">
                <div class="text-center mb-4">
                    <i class="fa-solid fa-user-shield text-gold display-3 mb-2"></i>
                    <h2 class="font-heading fw-bold">Wanderlust Admin</h2>
                    <p class="text-muted small">Sign in to manage destinations, packages, bookings, and users.</p>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger small py-2"><i class="fa-solid fa-circle-exclamation me-1"></i><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <form method="POST" action="login.php">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase text-muted">Admin Email</label>
                        <input type="email" name="email" class="form-control form-control-luxury" required value="admin@wanderlust.com">
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-uppercase text-muted">Password</label>
                        <input type="password" name="password" class="form-control form-control-luxury" required value="admin123">
                    </div>
                    <button type="submit" class="btn btn-gold btn-lg w-100 py-3 fw-bold fs-6">
                        <i class="fa-solid fa-right-to-bracket me-2"></i>Enter Admin Panel
                    </button>
                </form>

                <div class="mt-4 p-3 bg-light rounded border border-gold small text-center">
                    <strong class="text-main">Default Admin Account:</strong><br>
                    Email: <code>admin@wanderlust.com</code> | Password: <code>admin123</code>
                </div>

                <div class="text-center mt-3">
                    <a href="../index.php" class="text-muted small text-decoration-none"><i class="fa-solid fa-arrow-left me-1"></i>Back to Main Website</a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
