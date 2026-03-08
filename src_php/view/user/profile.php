<div class="container">
    <div class="profile-page">
        <div class="profile-header">
            <div class="profile-avatar-large">
                <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
            </div>
            <div class="profile-header-info">
                <h1><?php echo htmlspecialchars($user['username']); ?></h1>
                <p class="profile-email"><?php echo htmlspecialchars($user['email']); ?></p>
                <div class="profile-badge">
                    <span class="badge badge-<?php echo $user['role'] == 'admin' ? 'admin' : 'user'; ?>">
                        <?php echo ucfirst(htmlspecialchars($user['role'])); ?>
                    </span>
                </div>
            </div>
        </div>
        
        <?php if (isset($success) && $success): ?>
            <div class="alert alert-success">
                <?php echo isset($message) ? htmlspecialchars($message) : 'Profile updated successfully!'; ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 1.25rem;">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <div class="profile-stats">
            <div class="stat-card">
                <div class="stat-icon">📝</div>
                <div class="stat-content">
                    <div class="stat-value"><?php echo isset($reviewCount) ? $reviewCount : 0; ?></div>
                    <div class="stat-label">Reviews</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">📅</div>
                <div class="stat-content">
                    <div class="stat-value"><?php echo date('M Y', strtotime($user['created_at'])); ?></div>
                    <div class="stat-label">Member Since</div>
                </div>
            </div>
        </div>
        
        <div class="profile-content-grid">
            <div class="profile-card">
                <h2 class="profile-card-title">Account Information</h2>
                <form class="profile-form" method="POST" action="/user/profile">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" class="form-input" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                        <small class="form-hint">Username cannot be changed</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="form-input" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="role">Account Type</label>
                        <input type="text" id="role" class="form-input" value="<?php echo ucfirst(htmlspecialchars($user['role'])); ?>" disabled>
                    </div>
                    
                    <div class="form-group">
                        <label>Registration Date</label>
                        <input type="text" class="form-input" value="<?php echo date('F j, Y', strtotime($user['created_at'])); ?>" disabled>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-large">Save Changes</button>
                </form>
            </div>
            
            <div class="profile-card">
                <h2 class="profile-card-title">Change Password</h2>
                <p class="profile-card-description">Leave blank if you don't want to change your password</p>
                
                <form class="profile-form" method="POST" action="/user/profile">
                    <input type="hidden" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
                    
                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" id="current_password" name="current_password" class="form-input" placeholder="Enter current password">
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password" name="new_password" class="form-input" placeholder="Enter new password">
                        <small class="form-hint">At least 6 characters</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-input" placeholder="Confirm new password">
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-large">Update Password</button>
                </form>
            </div>
        </div>
        
        <div class="profile-actions">
            <a href="/user/reviews" class="btn btn-outline btn-large">
                <span>📝</span>
                <span>View My Reviews</span>
            </a>
            <a href="/user/logout" class="btn btn-outline btn-large">
                <span>🚪</span>
                <span>Logout</span>
            </a>
        </div>
    </div>
</div>

