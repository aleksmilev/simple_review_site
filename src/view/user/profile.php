<div class="container">
    <div class="legal-page">
        <h1>My Profile</h1>
        
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
        
        <div class="profile-section">
            <h2>Account Information</h2>
            <form class="auth-form" method="POST" action="/user/profile">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                    <small>Username cannot be changed</small>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="role">Role</label>
                    <input type="text" id="role" value="<?php echo ucfirst(htmlspecialchars($user['role'])); ?>" disabled>
                </div>
                
                <div class="form-group">
                    <label>Member Since</label>
                    <input type="text" value="<?php echo date('F j, Y', strtotime($user['created_at'])); ?>" disabled>
                </div>
                
                <h3 style="margin-top: 2rem; margin-bottom: 1rem;">Change Password</h3>
                <p style="color: #6b7280; margin-bottom: 1.5rem;">Leave blank if you don't want to change your password</p>
                
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password">
                </div>
                
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password">
                    <small>At least 6 characters</small>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password">
                </div>
                
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
        </div>
        
        <div class="profile-actions" style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #e5e7eb;">
            <a href="/user/reviews" class="btn btn-outline">View My Reviews</a>
            <a href="/user/logout" class="btn btn-outline" style="margin-left: 1rem;">Logout</a>
        </div>
    </div>
</div>

