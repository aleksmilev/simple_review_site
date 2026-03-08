<div class="container">
    <div class="admin-page">
        <h1>Manage Users</h1>
        
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if (empty($users)): ?>
            <div class="empty-state">
                <div class="empty-icon">👥</div>
                <h2>No Users</h2>
                <p>No users found.</p>
            </div>
        <?php else: ?>
            <div class="admin-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Reviews</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <?php $isCurrentUser = $user['id'] == $_SESSION['user_id']; ?>
                            <tr <?php echo $isCurrentUser ? 'style="opacity: 0.6; background-color: #f3f4f6;"' : ''; ?>>
                                <td><strong><?php echo htmlspecialchars($user['username']); ?></strong></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td>
                                    <form method="POST" action="/admin/users" style="display: inline;">
                                        <input type="hidden" name="action" value="update_role">
                                        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                        <select name="role" class="form-select-small" <?php echo $isCurrentUser ? 'disabled style="cursor: not-allowed;"' : ''; ?> onchange="<?php echo $isCurrentUser ? '' : 'this.form.submit()'; ?>">
                                            <option value="user" <?php echo $user['role'] == 'user' ? 'selected' : ''; ?>>User</option>
                                            <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                        </select>
                                    </form>
                                </td>
                                <td><?php echo $user['review_count']; ?></td>
                                <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                                <td>
                                    <div class="admin-actions">
                                        <?php if (!$isCurrentUser): ?>
                                            <form method="POST" action="/admin/users" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                                <button type="submit" class="btn btn-small btn-danger">Delete</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        
        <div style="margin-top: 2rem;">
            <a href="/admin" class="btn btn-outline">Back to Dashboard</a>
        </div>
    </div>
</div>

