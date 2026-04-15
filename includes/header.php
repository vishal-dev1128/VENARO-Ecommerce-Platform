<?php
declare(strict_types=1);

require_once __DIR__ . '/functions.php';

ensureSessionStarted();

$pageTitle = $pageTitle ?? 'Dashboard';
$flashMessage = getFlash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h(APP_NAME . ' | ' . $pageTitle); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="app-shell">
        <header class="topbar">
            <div class="brand">
                <h1><?= h(APP_NAME); ?></h1>
                <p>Daily order tracking and monthly earnings summary</p>
            </div>
            <?php if (isLoggedIn()): ?>
                <div class="topbar-actions">
                    <span class="user-chip">Signed in: <?= h(currentUserName()); ?></span>
                    <a class="btn btn-ghost" href="index.php">Dashboard</a>
                    <a class="btn btn-danger" href="logout.php">Logout</a>
                </div>
            <?php endif; ?>
        </header>
        <main class="page-body">
            <?php if ($flashMessage !== null): ?>
                <div class="alert alert-<?= h((string) $flashMessage['type']); ?>">
                    <?= h((string) $flashMessage['message']); ?>
                </div>
            <?php endif; ?>
