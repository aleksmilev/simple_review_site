<div class="container">
    <div class="legal-page">
        <h1>My Reviews</h1>
        <p class="intro-text">All reviews you have submitted on ReviewHub</p>
        
        <?php if (empty($reviews)): ?>
            <div class="empty-state">
                <div class="empty-icon">📝</div>
                <h2>No Reviews Yet</h2>
                <p>You haven't submitted any reviews yet. Start reviewing companies to help others make informed decisions!</p>
                <a href="/review/company" class="btn btn-primary">Browse Companies</a>
            </div>
        <?php else: ?>
            <div class="reviews-list">
                <?php foreach ($reviews as $review): ?>
                    <div class="review-card">
                        <div class="review-header">
                            <div class="review-rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="star <?php echo $i <= $review['rating'] ? 'filled' : ''; ?>">★</span>
                                <?php endfor; ?>
                            </div>
                            <div class="review-date">
                                <?php echo date('F j, Y', strtotime($review['created_at'])); ?>
                            </div>
                        </div>
                        
                        <?php if (!empty($review['title'])): ?>
                            <h3 class="review-title"><?php echo htmlspecialchars($review['title']); ?></h3>
                        <?php endif; ?>
                        
                        <p class="review-content"><?php echo nl2br(htmlspecialchars($review['content'])); ?></p>
                        
                        <div class="review-footer">
                            <span class="review-company">
                                Company ID: <?php echo $review['company_id']; ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <div style="margin-top: 2rem;">
            <a href="/user/profile" class="btn btn-outline">Back to Profile</a>
            <a href="/review/company" class="btn btn-primary" style="margin-left: 1rem;">Browse Companies</a>
        </div>
    </div>
</div>

