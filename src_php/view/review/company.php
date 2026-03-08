<div class="container">
    <div class="legal-page">
        <h1>Companies</h1>
        <p class="intro-text">Browse all companies and read reviews from the community</p>
        
        <?php if (empty($companies)): ?>
            <div class="empty-state">
                <div class="empty-icon">🏢</div>
                <h2>No Companies Found</h2>
                <p>There are no companies available yet.</p>
            </div>
        <?php else: ?>
            <div class="companies-grid">
                <?php foreach ($companies as $company): ?>
                    <div class="company-card">
                        <div class="company-card-header">
                            <h2>
                                <a href="/review/company/<?php echo $company['id']; ?>" class="company-link">
                                    <?php echo htmlspecialchars($company['name']); ?>
                                </a>
                            </h2>
                            <?php if ($company['total_reviews'] > 0): ?>
                                <div class="company-rating">
                                    <?php 
                                    $avgRating = round($company['average_rating'], 1);
                                    for ($i = 1; $i <= 5; $i++): 
                                    ?>
                                        <span class="star <?php echo $i <= $avgRating ? 'filled' : ''; ?>">★</span>
                                    <?php endfor; ?>
                                    <span class="rating-text"><?php echo $avgRating; ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php if (!empty($company['description'])): ?>
                            <p class="company-description"><?php echo htmlspecialchars(substr($company['description'], 0, 150)); ?><?php echo strlen($company['description']) > 150 ? '...' : ''; ?></p>
                        <?php endif; ?>
                        
                        <div class="company-card-footer">
                            <div class="company-stats">
                                <span class="stat-item">
                                    <strong><?php echo $company['total_reviews']; ?></strong> 
                                    <?php echo $company['total_reviews'] == 1 ? 'review' : 'reviews'; ?>
                                </span>
                            </div>
                            <a href="/review/company/<?php echo $company['id']; ?>" class="btn btn-primary btn-small">View Reviews</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

