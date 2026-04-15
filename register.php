<?php
declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

ensureSessionStarted();

if (isLoggedIn()) {
    redirect('index.php');
}

$username = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $confirmPassword = (string) ($_POST['confirm_password'] ?? '');

    if ($username === '') {
        $errors[] = 'Username is required.';
    } elseif (strlen($username) < 3) {
        $errors[] = 'Username must be at least 3 characters.';
    }

    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }

    if ($password !== $confirmPassword) {
        $errors[] = 'Password and confirm password must match.';
    }

    if ($errors === []) {
        $checkStmt = $pdo->prepare('SELECT id FROM users WHERE username = :username LIMIT 1');
        $checkStmt->execute(['username' => $username]);

        if ($checkStmt->fetch() !== false) {
            $errors[] = 'Username already exists.';
        } else {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $insertStmt = $pdo->prepare('INSERT INTO users (username, password) VALUES (:username, :password)');
            $insertStmt->execute([
                'username' => $username,
                'password' => $passwordHash,
            ]);

            setFlash('success', 'Registration complete. Please login.');
            redirect('login.php');
        }
    }
}

$pageTitle = 'Register';
require_once __DIR__ . '/includes/header.php';
?>

<section class="auth-card">
    <h2>Create Account</h2>
    <p class="subtle">Create your login for managing chapati sales.</p>

    <?php if ($errors !== []): ?>
        <div class="form-errors">
            <?php foreach ($errors as $error): ?>
                <p><?= h($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" class="stack-form">
        <label for="username">Username</label>
        <input id="username" name="username" type="text" maxlength="100" value="<?= h($username); ?>" required>

        <label for="password">Password</label>
        <input id="password" name="password" type="password" required>

        <label for="confirm_password">Confirm Password</label>
        <input id="confirm_password" name="confirm_password" type="password" required>

        <button type="submit" class="btn btn-primary full">Create Account</button>
    </form>

    <p class="subtle center mt-12">Already have an account? <a href="login.php">Login</a></p>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
