<div class="container">
    <div class="admin-page">
        <h1>Manage Reviews</h1>
        
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if (empty($reviews)): ?>
            <div class="empty-state">
                <div class="empty-icon">📝</div>
                <h2>No Reviews</h2>
                <p>No reviews found.</p>
            </div>
        <?php else: ?>
            <div class="admin-reviews-list">
                <?php foreach ($reviews as $review): ?>
                    <div class="admin-review-card">
                        <div class="admin-review-header">
                            <div class="admin-review-rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="star <?php echo $i <= $review['rating'] ? 'filled' : ''; ?>">★</span>
                                <?php endfor; ?>
                            </div>
                            <div class="admin-review-meta">
                                <span><strong>Company:</strong> <a href="/review/company/<?php echo $review['company_id']; ?>"><?php echo htmlspecialchars($review['company']['name'] ?? 'Unknown'); ?></a></span>
                                <span><strong>By:</strong> <?php echo isset($review['user']) ? htmlspecialchars($review['user']['username']) : 'Anonymous'; ?></span>
                                <span><strong>Date:</strong> <?php echo date('M j, Y', strtotime($review['created_at'])); ?></span>
                            </div>
                        </div>
                        
                        <?php if (!empty($review['title'])): ?>
                            <h3 class="admin-review-title"><?php echo htmlspecialchars($review['title']); ?></h3>
                        <?php endif; ?>
                        
                        <p class="admin-review-content"><?php echo nl2br(htmlspecialchars($review['content'])); ?></p>
                        
                        <div class="admin-review-actions">
                            <form method="POST" action="/admin/reviews" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this review?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $review['id']; ?>">
                                <button type="submit" class="btn btn-small btn-danger">Delete Review</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <div style="margin-top: 2rem;">
            <a href="/admin" class="btn btn-outline">Back to Dashboard</a>
        </div>
    </div>
</div>

