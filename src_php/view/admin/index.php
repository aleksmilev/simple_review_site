<div class="container">
    <div class="admin-page">
        <h1>Admin Dashboard</h1>
        
        <div class="admin-stats-grid">
            <div class="admin-stat-card">
                <div class="stat-icon">🏢</div>
                <div class="stat-content">
                    <div class="stat-value"><?php echo $totalCompanies; ?></div>
                    <div class="stat-label">Companies</div>
                </div>
                <a href="/admin/companies" class="stat-link">Manage →</a>
            </div>
            
            <div class="admin-stat-card">
                <div class="stat-icon">📝</div>
                <div class="stat-content">
                    <div class="stat-value"><?php echo $totalReviews; ?></div>
                    <div class="stat-label">Reviews</div>
                </div>
                <a href="/admin/reviews" class="stat-link">Manage →</a>
            </div>
            
            <div class="admin-stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-content">
                    <div class="stat-value"><?php echo $totalUsers; ?></div>
                    <div class="stat-label">Users</div>
                </div>
                <a href="/admin/users" class="stat-link">Manage →</a>
            </div>
            
            <div class="admin-stat-card">
                <div class="stat-icon">🏷️</div>
                <div class="stat-content">
                    <div class="stat-value"><?php echo $totalTags; ?></div>
                    <div class="stat-label">Tags</div>
                </div>
                <a href="/admin/tags" class="stat-link">Manage →</a>
            </div>
        </div>
        
        <div class="admin-quick-actions">
            <h2>Quick Actions</h2>
            <div class="quick-actions-grid">
                <a href="/admin/company/create" class="quick-action-card">
                    <div class="quick-action-icon">➕</div>
                    <div class="quick-action-label">Create Company</div>
                </a>
                <a href="/admin/tag/create" class="quick-action-card">
                    <div class="quick-action-icon">🏷️</div>
                    <div class="quick-action-label">Create Tag</div>
                </a>
            </div>
        </div>
        
        <div class="admin-section">
            <div class="admin-section-header">
                <h2>Recent Feedback</h2>
                <a href="/admin/feedback" class="btn btn-outline">View All</a>
            </div>
            
            <?php if (empty($recentFeedback)): ?>
                <div class="empty-state">
                    <p>No feedback yet.</p>
                </div>
            <?php else: ?>
                <div class="feedback-list">
                    <?php foreach ($recentFeedback as $item): ?>
                        <div class="feedback-item">
                            <div class="feedback-header">
                                <strong><?php echo htmlspecialchars($item['name']); ?></strong>
                                <span class="feedback-email"><?php echo htmlspecialchars($item['email']); ?></span>
                                <span class="feedback-date"><?php echo date('M j, Y', strtotime($item['created_at'])); ?></span>
                            </div>
                            <div class="feedback-subject"><?php echo htmlspecialchars($item['subject']); ?></div>
                            <div class="feedback-message"><?php echo nl2br(htmlspecialchars($item['message'])); ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

