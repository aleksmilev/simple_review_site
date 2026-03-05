<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Find and review companies. Share your experiences and discover trusted businesses.">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - ' : ''; ?>ReviewSite</title>
    <link rel="stylesheet" href="/assets/css/main.css">
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
                
                <div class="search-container">
                    <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="search" class="search-input" placeholder="Search companies..." aria-label="Search companies">
                </div>
                
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

