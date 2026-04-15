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

    if ($username === '') {
        $errors[] = 'Username is required.';
    }

    if ($password === '') {
        $errors[] = 'Password is required.';
    }

    if ($errors === []) {
        $stmt = $pdo->prepare('SELECT id, username, password FROM users WHERE username = :username LIMIT 1');
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if ($user === false || !password_verify($password, (string) $user['password'])) {
            $errors[] = 'Invalid username or password.';
        } else {
            $_SESSION['user_id'] = (int) $user['id'];
            $_SESSION['username'] = (string) $user['username'];

            setFlash('success', 'Login successful.');
            redirect('index.php');
        }
    }
}

$pageTitle = 'Login';
require_once __DIR__ . '/includes/header.php';
?>

<section class="auth-card">
    <h2>Login</h2>
    <p class="subtle">Access your chapati sales dashboard.</p>

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

        <button type="submit" class="btn btn-primary full">Login</button>
    </form>

    <p class="subtle center mt-12">No account yet? <a href="register.php">Create one</a></p>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
