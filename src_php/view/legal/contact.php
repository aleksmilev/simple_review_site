<div class="container">
    <?php if (isset($success) && $success): ?>
    <div class="success-popup" id="successPopup">
        <div class="success-popup-content">
            <div class="success-icon">✓</div>
            <h3>Message Sent Successfully!</h3>
            <p><?php echo isset($message) ? htmlspecialchars($message) : 'Thank you for contacting us. We will get back to you soon.'; ?></p>
            <button class="success-close" onclick="closeSuccessPopup()">Close</button>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="legal-page">
        <h1>Contact Us</h1>
        <p class="intro-text">We'd love to hear from you! Whether you have a question, feedback, or need support, feel free to reach out to us.</p>
        
        <div class="contact-grid">
            <div class="contact-section">
                <h2>Get in Touch</h2>
                <form class="contact-form" method="POST" action="/legal/contact" data-success="<?php echo (isset($success) && $success) ? 'true' : 'false'; ?>">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <select id="subject" name="subject" required>
                            <option selected disabled hidden>Select a subject</option>
                            <option value="general">General Inquiry</option>
                            <option value="support">Support Request</option>
                            <option value="feedback">Feedback</option>
                            <option value="report">Report an Issue</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" rows="6" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
            </div>
            
            <div class="contact-info">
                <h2>Other Ways to Reach Us</h2>
                <div class="info-item">
                    <h3>Email</h3>
                    <p>support@reviewhub.com</p>
                </div>
                
                <div class="info-item">
                    <h3>Response Time</h3>
                    <p>We typically respond within 24-48 hours during business days.</p>
                </div>
                
                <div class="info-item">
                    <h3>Business Hours</h3>
                    <p>Monday - Friday: 9:00 AM - 5:00 PM (EST)</p>
                </div>
            </div>
        </div>
    </div>
</div>
