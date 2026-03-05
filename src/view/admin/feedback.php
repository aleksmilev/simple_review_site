<div class="container">
    <div class="admin-page">
        <h1>View Feedback</h1>
        
        <?php if (empty($feedback)): ?>
            <div class="empty-state">
                <div class="empty-icon">📧</div>
                <h2>No Feedback</h2>
                <p>No feedback messages yet.</p>
            </div>
        <?php else: ?>
            <div class="feedback-list">
                <?php foreach ($feedback as $item): ?>
                    <div class="feedback-item">
                        <div class="feedback-header">
                            <strong><?php echo htmlspecialchars($item['name']); ?></strong>
                            <span class="feedback-email"><?php echo htmlspecialchars($item['email']); ?></span>
                            <span class="feedback-date"><?php echo date('M j, Y g:i A', strtotime($item['created_at'])); ?></span>
                        </div>
                        <div class="feedback-subject"><?php echo htmlspecialchars($item['subject']); ?></div>
                        <div class="feedback-message"><?php echo nl2br(htmlspecialchars($item['message'])); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <div style="margin-top: 2rem;">
            <a href="/admin" class="btn btn-outline">Back to Dashboard</a>
        </div>
    </div>
</div>

