<div class="container">
    <div class="legal-page">
        <div class="company-detail-header">
            <h1><?php echo htmlspecialchars($company['name']); ?></h1>
            
            <?php if (!empty($company['website'])): ?>
                <a href="<?php echo htmlspecialchars($company['website']); ?>" target="_blank" rel="noopener noreferrer" class="company-website">
                    Visit Website →
                </a>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($company['description'])): ?>
            <p class="intro-text"><?php echo nl2br(htmlspecialchars($company['description'])); ?></p>
        <?php endif; ?>
        
        <?php if (!empty($tags)): ?>
            <div class="company-tags">
                <h3>Tags</h3>
                <div class="tags-list">
                    <?php foreach ($tags as $tag): ?>
                        <a href="/review/search?tag=<?php echo $tag['id']; ?>" class="tag-badge" style="background-color: <?php echo htmlspecialchars($tag['color']); ?>20; color: <?php echo htmlspecialchars($tag['color']); ?>; border-color: <?php echo htmlspecialchars($tag['color']); ?>;">
                            <?php echo htmlspecialchars($tag['name']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="company-rating-summary">
            <?php if ($total_reviews > 0): ?>
                <div class="rating-display-large">
                    <?php 
                    $avgRating = round($average_rating, 1);
                    for ($i = 1; $i <= 5; $i++): 
                    ?>
                        <span class="star star-large <?php echo $i <= $avgRating ? 'filled' : ''; ?>">★</span>
                    <?php endfor; ?>
                    <div class="rating-info">
                        <span class="rating-value"><?php echo $avgRating; ?></span>
                        <span class="rating-count">Based on <?php echo $total_reviews; ?> <?php echo $total_reviews == 1 ? 'review' : 'reviews'; ?></span>
                    </div>
                </div>
            <?php else: ?>
                <div class="no-reviews-yet">
                    <p>No reviews yet. Be the first to review this company!</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="add-review-section">
            <h2>Write a Review</h2>
            <p class="form-intro">Share your experience with <?php echo htmlspecialchars($company['name']); ?></p>
            
            <?php if (isset($errors) && !empty($errors)): ?>
                <div class="alert alert-error">
                    <ul style="margin: 0; padding-left: 1.25rem;">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <?php if (isset($success) && $success): ?>
                <div class="alert alert-success">
                    <?php echo isset($message) ? htmlspecialchars($message) : 'Review submitted successfully!'; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="/review/company/<?php echo $company['id']; ?>" class="review-form">
                <input type="hidden" name="company_id" value="<?php echo $company['id']; ?>">
                
                <div class="form-group">
                    <label for="rating">Rating <span class="required">*</span></label>
                    <div class="rating-select-wrapper">
                        <select id="rating" name="rating" class="rating-select" required>
                            <option selected disabled hidden>Select a rating</option>
                            <option value="5">5 - Excellent ⭐⭐⭐⭐⭐</option>
                            <option value="4">4 - Very Good ⭐⭐⭐⭐</option>
                            <option value="3">3 - Good ⭐⭐⭐</option>
                            <option value="2">2 - Fair ⭐⭐</option>
                            <option value="1">1 - Poor ⭐</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="title">Review Title <span class="optional">(Optional)</span></label>
                    <input type="text" id="title" name="title" placeholder="e.g., Great service and friendly staff" class="form-input">
                </div>
                
                <div class="form-group">
                    <label for="content">Your Review <span class="required">*</span></label>
                    <textarea id="content" name="content" rows="6" placeholder="Tell us about your experience. What did you like? What could be improved?" class="form-textarea" required></textarea>
                    <small class="form-hint">Minimum 10 characters</small>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-large">Submit Review</button>
                </div>
            </form>
        </div>
        
        <?php if (empty($reviews)): ?>
            <div class="empty-state">
                <div class="empty-icon">📝</div>
                <h2>No Reviews Yet</h2>
                <p>This company doesn't have any reviews yet. Be the first to share your experience!</p>
            </div>
        <?php else: ?>
            <div class="reviews-section">
                <h2>Reviews</h2>
                <div class="reviews-list">
                    <?php foreach ($reviews as $review): ?>
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
        <?php endif; ?>
        
        <div style="margin-top: 2rem;">
            <a href="/review/company" class="btn btn-outline">Back to Companies</a>
        </div>
    </div>
</div>

