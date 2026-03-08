<div class="container">
    <div class="auth-page">
        <div class="auth-card">
            <h1>Login</h1>
            <p class="auth-subtitle">Sign in to your account to continue</p>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form class="auth-form" method="POST" action="/user/login">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required autofocus>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </form>
            
            <div class="auth-footer">
                <p>Don't have an account? <a href="/user/register">Sign up here</a></p>
            </div>
        </div>
    </div>
</div>

