<?php
require_once '../config/database.php';
require_once '../includes/session.php';

$error = '';
$success = '';

if (isset($_GET['registered']) && $_GET['registered'] === '1') {
    $success = 'Account created successfully! You can now log in.';
}

if (is_logged_in()) {
    header('Location: ../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Please enter both username and password.';
    } else {
        // Prepared statements help protect the login query from SQL injection.
        $statement = $pdo->prepare('SELECT id, username, password FROM users WHERE username = ? LIMIT 1');
        $statement->execute([$username]);
        $user = $statement->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            header('Location: ../index.php');
            exit;
        }

        $error = 'Invalid username or password.';
    }
}

$page_title = 'Login';
$page_description = 'Access the software project tracking dashboard.';
$base_path = '..';
$active_page = 'login';

require_once '../includes/header.php';
?>

<main class="page-shell auth-layout">
    <section class="auth-card">
        <span class="eyebrow">Secure Access</span>
        <h2>Welcome Back</h2>
        <p>Sign in with your school administrator account.</p>

        <?php if ($error !== ''): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success !== ''): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST" class="stacked-form">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" autocomplete="username" placeholder="JaneDoe" required>
            </div>

            <div class="form-group password-field">
                <label for="password">Password</label>
                <div class="input-action">
                    <input type="password" id="password" name="password" autocomplete="current-password" placeholder="***" required>
                    <button class="ghost-button password-toggle" type="button" data-target="password"><i class="fa-regular fa-eye"></i></button>
                </div>
            </div>

            <button class="button button-primary full-width" type="submit">Login</button>
        </form>
        <div class="auth-helper">
            Don't have an account? <a href="register.php">Register here</a>
        </div>
    </section>
</main>

<?php require_once '../includes/footer.php'; ?>
