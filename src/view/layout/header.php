<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Find and review companies. Share your experiences and discover trusted businesses.">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - ' : ''; ?>ReviewSite</title>
    <link rel="stylesheet" href="/assets/css/reset.css">
    <link rel="stylesheet" href="/assets/css/header.css">
    <link rel="stylesheet" href="/assets/css/footer.css">
    <link rel="stylesheet" href="/assets/css/layout.css">
    <link rel="stylesheet" href="/assets/css/components.css">
    <link rel="stylesheet" href="/assets/css/home.css">
    <link rel="stylesheet" href="/assets/css/legal.css">
    <link rel="stylesheet" href="/assets/css/auth.css">
    <link rel="stylesheet" href="/assets/css/popup.css">
    <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body>
    <header class="header">
        <div class="header-container">
            <div class="header-content">
                <a href="/home" class="logo">
                    <div class="logo-icon">R</div>
                    <span>ReviewSite</span>
                </a>
                
                <nav class="nav">
                    <ul class="nav-links">
                        <li><a href="/home" class="nav-link <?php echo (isset($currentPage) && $currentPage == 'home') ? 'active' : ''; ?>">Home</a></li>
                        <li><a href="/review/company" class="nav-link <?php echo (isset($currentPage) && $currentPage == 'companies') ? 'active' : ''; ?>">Companies</a></li>
                        <li><a href="/review" class="nav-link <?php echo (isset($currentPage) && $currentPage == 'reviews') ? 'active' : ''; ?>">Reviews</a></li>
                    </ul>
                </nav>
                
                <form method="POST" action="/review/search" class="search-container">
                    <button type="submit" class="search-icon-button" aria-label="Search companies">
                        <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                    <input type="search" name="query" class="search-input" placeholder="Search companies..." aria-label="Search companies">
                </form>
                
                <div class="user-actions">
                    <?php if (isset($user) && $user): ?>
                        <div class="user-menu">
                            <div class="user-avatar" title="<?php echo htmlspecialchars($user['username']); ?>">
                                <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                            </div>
                        </div>
                        <?php if (isset($user['role']) && $user['role'] == 'admin'): ?>
                            <a href="/admin" class="btn btn-outline">
                                <span class="btn-text">Admin</span>
                            </a>
                        <?php endif; ?>
                        <a href="/user/logout" class="btn btn-outline">
                            <span class="btn-text">Logout</span>
                        </a>
                    <?php else: ?>
                        <a href="/user/login" class="btn btn-outline">
                            <span class="btn-text">Login</span>
                        </a>
                        <a href="/user/register" class="btn btn-primary">
                            <span class="btn-text">Sign Up</span>
                        </a>
                    <?php endif; ?>
                    <button class="mobile-menu-toggle" aria-label="Toggle menu">
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </header>
    
    <main>

