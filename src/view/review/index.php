<div class="container">
    <div class="legal-page">
        <h1>All Reviews</h1>
        <p class="intro-text">Browse reviews grouped by company</p>
        
        <?php if (empty($reviewsByCompany)): ?>
            <div class="empty-state">
                <div class="empty-icon">📝</div>
                <h2>No Reviews Yet</h2>
                <p>There are no reviews available yet. Be the first to review a company!</p>
                <a href="/review/company" class="btn btn-primary">Browse Companies</a>
            </div>
        <?php else: ?>
            <div class="companies-reviews-list">
                <?php foreach ($reviewsByCompany as $companyId => $data): ?>
                    <div class="company-reviews-section">
                        <div class="company-header">
                            <h2>
                                <a href="/review/company/<?php echo $companyId; ?>" class="company-link">
                                    <?php echo htmlspecialchars($data['company']['name']); ?>
                                </a>
                            </h2>
                            <div class="company-stats">
                                <div class="rating-display">
                                    <?php 
                                    $avgRating = round($data['average_rating'], 1);
                                    for ($i = 1; $i <= 5; $i++): 
                                    ?>
                                        <span class="star <?php echo $i <= $avgRating ? 'filled' : ''; ?>">★</span>
                                    <?php endfor; ?>
                                    <span class="rating-text"><?php echo $avgRating; ?> (<?php echo $data['total_reviews']; ?> reviews)</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="reviews-list">
                            <?php foreach ($data['reviews'] as $review): ?>
                                <div class="review-card">
                                    <div class="review-header">
                                        <div class="review-rating">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <span class="star <?php echo $i <= $review['rating'] ? 'filled' : ''; ?>">★</span>
                                            <?php endfor; ?>
                                        </div>
                                        <div class="review-meta">
                                            <?php if (isset($review['user'])): ?>
                                                <span class="review-author">By <?php echo htmlspecialchars($review['user']['username']); ?></span>
                                            <?php else: ?>
                                                <span class="review-author">Anonymous</span>
                                            <?php endif; ?>
                                            <span class="review-date">
                                                <?php echo date('F j, Y', strtotime($review['created_at'])); ?>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <?php if (!empty($review['title'])): ?>
                                        <h3 class="review-title"><?php echo htmlspecialchars($review['title']); ?></h3>
                                    <?php endif; ?>
                                    
                                    <p class="review-content"><?php echo nl2br(htmlspecialchars($review['content'])); ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

