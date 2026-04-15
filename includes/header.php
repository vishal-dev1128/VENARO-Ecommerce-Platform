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
    <!-- Premium Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        :root {
            --sidebar-width: 280px;
            --topbar-height: 70px;
            --sidebar-bg: #111111;
            --main-bg: #f8fafc;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--main-bg);
            color: #1e293b;
        }
        .app-shell {
            display: grid;
            grid-template-columns: var(--sidebar-width) 1fr;
            min-height: 100vh;
        }
        .sidebar {
            background-color: var(--sidebar-bg);
            color: #fff;
            padding: 24px;
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            border-right: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-brand {
            margin-bottom: 40px;
            padding: 0 8px;
        }
        .sidebar-brand h2 {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            letter-spacing: 2px;
            font-size: 1.5rem;
            color: #fff;
            margin: 0;
        }
        .nav-group {
            margin-bottom: 24px;
        }
        .nav-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #64748b;
            margin-bottom: 12px;
            display: block;
            font-weight: 700;
        }
        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            color: #cbd5e1;
            text-decoration: none;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s;
            margin-bottom: 4px;
        }
        .nav-link:hover, .nav-link.active {
            background-color: rgba(255,255,255,0.05);
            color: #fff;
        }
        .nav-link.active {
            background-color: rgba(255,255,255,0.1);
        }
        .main-container {
            display: flex;
            flex-direction: column;
        }
        .topbar {
            height: var(--topbar-height);
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #64748b;
            font-size: 0.85rem;
        }
        .page-content {
            padding: 32px;
            max-width: 1400px;
            width: 100%;
            margin: 0 auto;
        }
        .card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar">
            <div class="sidebar-brand">
                <h2>VÉNARO</h2>
            </div>
            
            <nav class="sidebar-nav">
                <div class="nav-group">
                    <span class="nav-label">Main</span>
                    <a href="index.php" class="nav-link active">
                        <i data-lucide="layout-dashboard" size="18"></i>
                        <span>Dashboard</span>
                    </a>
                </div>

                <div class="nav-group">
                    <span class="nav-label">Product & Stock</span>
                    <a href="#" class="nav-link">
                        <i data-lucide="package" size="18"></i>
                        <span>Products</span>
                    </a>
                    <a href="#" class="nav-link">
                        <i data-lucide="layers" size="18"></i>
                        <span>Categories</span>
                    </a>
                </div>

                <div class="nav-group">
                    <span class="nav-label">Sales & Orders</span>
                    <a href="#" class="nav-link">
                        <i data-lucide="shopping-cart" size="18"></i>
                        <span>Orders</span>
                    </a>
                    <a href="#" class="nav-link">
                        <i data-lucide="ticket" size="18"></i>
                        <span>Coupons</span>
                    </a>
                </div>

                <div class="nav-group" style="margin-top: 24px;">
                    <span class="nav-label">Upgrade</span>
                    <a href="https://vishaldev1.gumroad.com/l/VENARO" target="_blank" class="nav-link" style="background: #3b82f6; color: #fff; font-weight: 700;">
                        <i data-lucide="shopping-bag" size="18"></i>
                        <span>Buy Full Website</span>
                    </a>
                </div>

                <?php if (isLoggedIn()): ?>
                <div class="nav-group" style="margin-top: auto;">
                    <span class="nav-label">Session</span>
                    <a href="logout.php" class="nav-link" style="color: #f87171;">
                        <i data-lucide="log-out" size="18"></i>
                        <span>Logout</span>
                    </a>
                </div>
                <?php endif; ?>
            </nav>
        </aside>

        <div class="main-container">
            <header class="topbar">
                <div class="breadcrumb">
                    <span>Admin</span>
                    <i data-lucide="chevron-right" size="14"></i>
                    <span style="color: #1e293b; font-weight: 600;"><?= h($pageTitle); ?></span>
                </div>
                
                <?php if (isLoggedIn()): ?>
                <div class="topbar-actions">
                    <div style="display: flex; align-items: center; gap: 12px; background: #f1f5f9; padding: 6px 12px; border-radius: 99px;">
                        <div style="width: 28px; height: 28px; background: #334155; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 700;">
                            <?= strtoupper(substr(currentUserName(), 0, 1)); ?>
                        </div>
                        <span style="font-size: 0.85rem; font-weight: 600; color: #334155;"><?= h(currentUserName()); ?></span>
                    </div>
                </div>
                <?php endif; ?>
            </header>

            <main class="page-content">
                <?php if ($flashMessage !== null): ?>
                    <div class="alert alert-<?= h((string) $flashMessage['type']); ?>" style="margin-bottom: 24px; padding: 16px; border-radius: 12px; border: 1px solid; <?= $flashMessage['type'] === 'success' ? 'background: #f0fdf4; border-color: #bcf0da; color: #166534;' : 'background: #fef2f2; border-color: #fecaca; color: #991b1b;' ?>">
                        <?= h((string) $flashMessage['message']); ?>
                    </div>
                <?php endif; ?>
