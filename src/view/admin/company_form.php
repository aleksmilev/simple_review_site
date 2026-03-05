<div class="container">
    <div class="admin-page">
        <h1><?php echo isset($company) ? 'Edit Company' : 'Create Company'; ?></h1>
        
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
            <form method="POST" action="/admin/company/<?php echo isset($company) ? 'edit/' . $company['id'] : 'create'; ?>" id="companyForm">
                <div class="form-group">
                    <label for="name">Company Name *</label>
                    <input type="text" id="name" name="name" class="form-input" value="<?php echo htmlspecialchars($post['name'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="slug">Slug</label>
                    <input type="text" id="slug" name="slug" class="form-input" value="<?php echo htmlspecialchars($post['slug'] ?? ''); ?>" placeholder="auto-generated if empty">
                    <small class="form-hint">URL-friendly identifier (e.g., company-name)</small>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-textarea" rows="5"><?php echo htmlspecialchars($post['description'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="website">Website URL</label>
                    <input type="url" id="website" name="website" class="form-input" value="<?php echo htmlspecialchars($post['website'] ?? ''); ?>" placeholder="https://example.com">
                </div>
                
                <div class="form-group">
                    <label>Tags</label>
                    <div class="tags-checkbox-list">
                        <?php foreach ($tags as $tag): ?>
                            <label class="tag-checkbox">
                                <input type="checkbox" name="tags[]" value="<?php echo $tag['id']; ?>" <?php echo (isset($selectedTagIds) && in_array($tag['id'], $selectedTagIds)) ? 'checked' : ''; ?>>
                                <span class="tag-badge" style="background-color: <?php echo htmlspecialchars($tag['color']); ?>20; color: <?php echo htmlspecialchars($tag['color']); ?>; border-color: <?php echo htmlspecialchars($tag['color']); ?>;">
                                    <?php echo htmlspecialchars($tag['name']); ?>
                                </span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-large"><?php echo isset($company) ? 'Update Company' : 'Create Company'; ?></button>
                    <a href="/admin/companies" class="btn btn-outline btn-large">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

