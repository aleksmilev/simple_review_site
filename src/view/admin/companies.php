<div class="container">
    <div class="admin-page">
        <div class="admin-header">
            <h1>Manage Companies</h1>
            <a href="/admin/company/create" class="btn btn-primary">Create Company</a>
        </div>
        
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if (empty($companies)): ?>
            <div class="empty-state">
                <div class="empty-icon">🏢</div>
                <h2>No Companies</h2>
                <p>Get started by creating your first company.</p>
                <a href="/admin/company/create" class="btn btn-primary">Create Company</a>
            </div>
        <?php else: ?>
            <div class="admin-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Rating</th>
                            <th>Reviews</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($companies as $company): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($company['name']); ?></strong>
                                    <?php if (!empty($company['website'])): ?>
                                        <br><small><a href="<?php echo htmlspecialchars($company['website']); ?>" target="_blank"><?php echo htmlspecialchars($company['website']); ?></a></small>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars(substr($company['description'] ?? '', 0, 100)); ?><?php echo strlen($company['description'] ?? '') > 100 ? '...' : ''; ?></td>
                                <td>
                                    <?php if ($company['total_reviews'] > 0): ?>
                                        <?php 
                                        $avgRating = round($company['average_rating'], 1);
                                        for ($i = 1; $i <= 5; $i++): 
                                        ?>
                                            <span class="star <?php echo $i <= $avgRating ? 'filled' : ''; ?>">★</span>
                                        <?php endfor; ?>
                                        <span class="rating-text"><?php echo $avgRating; ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">No reviews</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $company['total_reviews']; ?></td>
                                <td><?php echo date('M j, Y', strtotime($company['created_at'])); ?></td>
                                <td>
                                    <div class="admin-actions">
                                        <a href="/review/company/<?php echo $company['id']; ?>" class="btn btn-small btn-outline">View</a>
                                        <a href="/admin/company/edit/<?php echo $company['id']; ?>" class="btn btn-small btn-outline">Edit</a>
                                        <form method="POST" action="/admin/companies" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this company?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $company['id']; ?>">
                                            <button type="submit" class="btn btn-small btn-danger">Delete</button>
                                        </form>
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

