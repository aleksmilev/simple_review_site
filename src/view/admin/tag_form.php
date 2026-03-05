<div class="container">
    <div class="admin-page">
        <h1><?php echo isset($tag) ? 'Edit Tag' : 'Create Tag'; ?></h1>
        
        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 1.25rem;">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <div class="admin-form-card">
            <form method="POST" action="/admin/tag/<?php echo isset($tag) ? 'edit/' . $tag['id'] : 'create'; ?>">
                <div class="form-group">
                    <label for="name">Tag Name *</label>
                    <input type="text" id="name" name="name" class="form-input" value="<?php echo htmlspecialchars($post['name'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="color">Color *</label>
                    <div style="display: flex; gap: 1rem; align-items: center;">
                        <input type="color" id="color" name="color" value="<?php echo htmlspecialchars($post['color'] ?? '#3b82f6'); ?>" style="width: 80px; height: 40px; border-radius: 0.5rem; border: 2px solid #d1d5db; cursor: pointer;">
                        <input type="text" class="form-input" value="<?php echo htmlspecialchars($post['color'] ?? '#3b82f6'); ?>" onchange="document.getElementById('color').value = this.value" placeholder="#3b82f6" style="flex: 1;">
                    </div>
                    <small class="form-hint">Choose a color for this tag</small>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-textarea" rows="4"><?php echo htmlspecialchars($post['description'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-large"><?php echo isset($tag) ? 'Update Tag' : 'Create Tag'; ?></button>
                    <a href="/admin/tags" class="btn btn-outline btn-large">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

