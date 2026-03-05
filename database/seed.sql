INSERT INTO users (username, email, password_hash, role) VALUES
('admin', 'admin@reviewhub.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('john_doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
('jane_smith', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
('mike_jones', 'mike@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
('admin_sarah', 'sarah@reviewhub.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

INSERT INTO tags (name, color, description) VALUES
('Technology', '#3B82F6', 'Technology and software companies'),
('Healthcare', '#10B981', 'Healthcare and medical services'),
('Finance', '#F59E0B', 'Financial services and banking'),
('Retail', '#EF4444', 'Retail and e-commerce businesses'),
('Food & Beverage', '#8B5CF6', 'Restaurants and food services'),
('Education', '#EC4899', 'Educational institutions and services'),
('Transportation', '#06B6D4', 'Transportation and logistics'),
('Real Estate', '#F97316', 'Real estate and property services');

INSERT INTO companies (name, description, slug, website, created_by) VALUES
('TechCorp Solutions', 'Leading provider of enterprise software solutions and cloud services.', 'techcorp-solutions', 'https://www.techcorp.com', 1),
('MediCare Plus', 'Comprehensive healthcare services with a focus on patient care and modern facilities.', 'medicare-plus', 'https://www.medicareplus.com', 1),
('Global Finance Bank', 'International banking services with competitive rates and excellent customer service.', 'global-finance-bank', 'https://www.globalfinance.com', 5),
('ShopSmart Retail', 'Modern retail chain offering a wide variety of products at competitive prices.', 'shopsmart-retail', 'https://www.shopsmart.com', 1),
('Bella Italia Restaurant', 'Authentic Italian cuisine in a cozy atmosphere. Family-owned since 1995.', 'bella-italia-restaurant', 'https://www.bellaitalia.com', 5),
('LearnTech Academy', 'Online learning platform offering courses in technology, business, and creative skills.', 'learntech-academy', 'https://www.learntech.com', 1),
('FastTrack Logistics', 'Reliable shipping and logistics services for businesses of all sizes.', 'fasttrack-logistics', 'https://www.fasttrack.com', 5),
('Prime Properties', 'Real estate agency specializing in residential and commercial properties.', 'prime-properties', 'https://www.primeproperties.com', 1);

INSERT INTO reviews (company_id, user_id, rating, title, content) VALUES
(1, 2, 5, 'Excellent Software Solutions', 'TechCorp has transformed our business operations. Their software is intuitive and their support team is always responsive. Highly recommended!'),
(1, NULL, 4, 'Good but could improve', 'The software works well overall, but the user interface could be more modern. Customer service is great though.'),
(1, 3, 5, 'Outstanding Service', 'Best enterprise solution we have used. The implementation was smooth and the training was comprehensive.'),

(2, 3, 5, 'Top-notch Healthcare', 'The staff at MediCare Plus is professional and caring. The facilities are clean and modern. I feel confident in their care.'),
(2, NULL, 3, 'Average experience', 'The service was okay, but wait times were longer than expected. Staff was friendly though.'),
(2, 4, 4, 'Good healthcare provider', 'Overall a positive experience. The doctors are knowledgeable and the facility is well-maintained.'),

(3, 2, 4, 'Reliable Banking Services', 'Global Finance Bank offers competitive rates and their online banking platform is user-friendly. Customer service could be faster.'),
(3, NULL, 5, 'Great bank', 'Switched to Global Finance Bank last year and have been very happy. No hidden fees and great customer support.'),
(3, 4, 3, 'Decent but not exceptional', 'The bank works fine for basic needs, but I wish they had more branch locations in my area.'),

(4, NULL, 4, 'Good prices and selection', 'ShopSmart has great deals and a wide selection. The store layout could be better organized though.'),
(4, 3, 5, 'My go-to store', 'I shop at ShopSmart regularly. Great prices, good quality products, and friendly staff.'),
(4, 2, 4, 'Solid retail chain', 'Good variety of products at reasonable prices. The checkout process is usually quick.'),

(5, 4, 5, 'Authentic Italian Cuisine', 'Bella Italia serves the best Italian food in town! The pasta is homemade and the atmosphere is perfect for a date night.'),
(5, NULL, 4, 'Delicious food', 'Great food and reasonable prices. The service was a bit slow on a busy night, but the food made up for it.'),
(5, 2, 5, 'Amazing experience', 'Every dish we tried was excellent. The staff is knowledgeable about the menu and very welcoming.'),

(6, 3, 5, 'Excellent Online Courses', 'LearnTech Academy has helped me advance my career. The courses are well-structured and the instructors are experts in their fields.'),
(6, NULL, 4, 'Good learning platform', 'The courses are informative and the platform is easy to use. Would like to see more advanced topics available.'),
(6, 4, 5, 'Highly recommend', 'I have completed several courses and each one exceeded my expectations. Great value for money.'),

(7, 2, 4, 'Reliable Shipping', 'FastTrack has been our logistics partner for 2 years. They are reliable and handle our shipments with care.'),
(7, NULL, 3, 'Average service', 'Shipping times are okay but not exceptional. The tracking system works well though.'),
(7, 3, 4, 'Good logistics company', 'They handle our business shipments efficiently. Customer service is responsive when we have questions.'),

(8, 4, 5, 'Found our dream home', 'Prime Properties helped us find the perfect home. The agents were professional and guided us through the entire process.'),
(8, NULL, 4, 'Helpful real estate agency', 'The agents were knowledgeable about the local market and helped us find a good property within our budget.'),
(8, 2, 3, 'Decent service', 'The service was adequate, but I felt the agents could have been more proactive in showing us properties.');

INSERT INTO company_tags (company_id, tag_id) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(6, 1),
(7, 7),
(8, 8);

INSERT INTO feedback (name, email, subject, message) VALUES
('Sarah Johnson', 'sarah.johnson@email.com', 'General Inquiry', 'I love your review platform! It has been incredibly helpful in finding reliable companies. Keep up the great work!'),
('Michael Chen', 'mchen@example.com', 'Support Request', 'I am having trouble updating my review. The edit button does not seem to be working. Could you please help?'),
('Emily Rodriguez', 'emily.r@email.com', 'Feedback', 'The website is great, but I would love to see a feature where I can filter reviews by date. This would help me see the most recent feedback.'),
('David Thompson', 'dthompson@example.com', 'Report an Issue', 'I noticed a review that contains inappropriate content. Please review and remove it if necessary.'),
('Lisa Anderson', 'lisa.anderson@email.com', 'General Inquiry', 'How do I become a verified reviewer? I have been using the platform for a while and would like to get verified status.'),
('Robert Williams', 'rwilliams@email.com', 'Feedback', 'The search functionality works well, but it would be better if it could also search within review content, not just company names.'),
('Jennifer Martinez', 'j.martinez@example.com', 'Support Request', 'I forgot my password and the reset link is not working. Can you help me regain access to my account?'),
('James Brown', 'james.brown@email.com', 'General Inquiry', 'Do you have an API available for developers? I would like to integrate your review data into my application.'),
('Amanda Davis', 'amanda.d@example.com', 'Feedback', 'The mobile version of the site is excellent! Very user-friendly and responsive. Great job on the design.'),
('Christopher Wilson', 'cwilson@email.com', 'Report an Issue', 'I believe there is a duplicate company listing. "TechCorp Solutions" appears twice with slightly different names. Please merge them.');