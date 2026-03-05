<div class="container">
    <div class="auth-page">
        <div class="auth-card">
            <h1>Create Account</h1>
            <p class="auth-subtitle">Join ReviewSite and start sharing your experiences</p>
            
            <?php if (isset($success) && $success): ?>
                <div class="alert alert-success">
                    <?php echo isset($message) ? htmlspecialchars($message) : 'Registration successful!'; ?>
                    <p style="margin-top: 0.5rem;"><a href="/user/login">Click here to login</a></p>
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
            
            <form class="auth-form" method="POST" action="/user/register">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required autofocus>
                    <small>At least 3 characters</small>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                    <small>At least 6 characters</small>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">Create Account</button>
            </form>
            
            <div class="auth-footer">
                <p>Already have an account? <a href="/user/login">Sign in here</a></p>
            </div>
        </div>
    </div>
</div>

