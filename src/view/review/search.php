<div class="container">
    <div class="legal-page">
        <h1>Search Results</h1>
        <p class="intro-text">Search results for: "<strong><?php echo htmlspecialchars($query); ?></strong>"</p>
        
        <div style="margin-bottom: 2rem;">
            <form method="POST" action="/review/search" class="search-form">
                <div class="form-group" style="display: flex; gap: 1rem; align-items: flex-end;">
                    <div style="flex: 1;">
                        <input type="text" name="query" value="<?php echo htmlspecialchars($query); ?>" placeholder="Search companies..." required>
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>
        </div>
        
        <?php if (empty($companies)): ?>
            <div class="empty-state">
                <div class="empty-icon">🔍</div>
                <h2>No Results Found</h2>
                <p>No companies found matching your search query. Try a different search term.</p>
                <a href="/review/company" class="btn btn-primary">Browse All Companies</a>
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
        
        <div style="margin-top: 2rem;">
            <a href="/review/company" class="btn btn-outline">Back to Companies</a>
        </div>
    </div>
</div>

