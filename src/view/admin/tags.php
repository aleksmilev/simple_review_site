<div class="container">
    <div class="admin-page">
        <div class="admin-header">
            <h1>Manage Tags</h1>
            <a href="/admin/tag/create" class="btn btn-primary">Create Tag</a>
        </div>
        
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if (empty($tags)): ?>
            <div class="empty-state">
                <div class="empty-icon">🏷️</div>
                <h2>No Tags</h2>
                <p>Get started by creating your first tag.</p>
                <a href="/admin/tag/create" class="btn btn-primary">Create Tag</a>
            </div>
        <?php else: ?>
            <div class="admin-tags-grid">
                <?php foreach ($tags as $tag): ?>
                    <div class="admin-tag-card">
                        <div class="admin-tag-header" style="background-color: <?php echo htmlspecialchars($tag['color']); ?>20; border-left: 4px solid <?php echo htmlspecialchars($tag['color']); ?>;">
                            <h3 style="color: <?php echo htmlspecialchars($tag['color']); ?>;"><?php echo htmlspecialchars($tag['name']); ?></h3>
                        </div>
                        <div class="admin-tag-content">
                            <?php if (!empty($tag['description'])): ?>
                                <p><?php echo htmlspecialchars($tag['description']); ?></p>
                            <?php endif; ?>
                            <div class="admin-tag-stats">
                                <span><strong><?php echo $tag['company_count']; ?></strong> companies</span>
                            </div>
                        </div>
                        <div class="admin-tag-actions">
                            <a href="/admin/tag/edit/<?php echo $tag['id']; ?>" class="btn btn-small btn-outline">Edit</a>
                            <form method="POST" action="/admin/tags" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this tag?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $tag['id']; ?>">
                                <button type="submit" class="btn btn-small btn-danger">Delete</button>
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

