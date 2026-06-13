<?php
require_once '../config/database.php';
require_once '../includes/session.php';

$error = '';

if (is_logged_in()) {
    header('Location: ../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($username === '' || $password === '' || $confirm_password === '') {
        $error = 'Please fill in all fields.';
    } elseif (strlen($username) < 3) {
        $error = 'Username must be at least 3 characters long.';
    } elseif (strlen($username) > 100) {
        $error = 'Username cannot exceed 100 characters.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } else {
        try {
            // Check if username already exists using a prepared statement to prevent SQL Injection
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE username = ?');
            $stmt->execute([$username]);
            $exists = $stmt->fetchColumn();

            if ($exists > 0) {
                $error = 'Username is already taken.';
            } else {
                // Securely hash the password before saving
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert user into the database using a prepared statement
                $insert_stmt = $pdo->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
                $insert_stmt->execute([$username, $hashed_password]);

                // Redirect to login page with a success flag
                header('Location: login.php?registered=1');
                exit;
            }
        } catch (PDOException $e) {
            $error = 'An error occurred during registration. Please try again.';
        }
    }
}

$page_title = 'Register';
$page_description = 'Create a secure account to track software projects.';
$base_path = '..';
$active_page = 'register';

require_once '../includes/header.php';
?>

<main class="page-shell auth-layout">
    <section class="auth-card">
        <span class="eyebrow">Create Account</span>
        <h2>Get Started</h2>
        <p>Register a new system administrator account.</p>

        <?php if ($error !== ''): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="register.php" method="POST" class="stacked-form">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" autocomplete="username" value="<?php echo htmlspecialchars($username ?? ''); ?>" placeholder="JaneDoe" required>
            </div>

            <div class="form-group password-field">
                <label for="password">Password</label>
                <div class="input-action">
                    <input type="password" id="password" name="password" autocomplete="new-password" placeholder="***" required>
                    <button class="ghost-button password-toggle" type="button" data-target="password"><i class="fa-regular fa-eye"></i></button>
                </div>
            </div>

            <div class="form-group password-field">
                <label for="confirm_password">Confirm Password</label>
                <div class="input-action">
                    <input type="password" id="confirm_password" name="confirm_password" autocomplete="new-password" placeholder="***" required>
                    <button class="ghost-button password-toggle" type="button" data-target="confirm_password"><i class="fa-regular fa-eye"></i></button>
                </div>
            </div>

            <button class="button button-primary full-width" type="submit">Register</button>
        </form>
        <div class="auth-helper">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </section>
</main>

<?php require_once '../includes/footer.php'; ?>
