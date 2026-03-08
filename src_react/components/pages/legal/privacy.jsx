import { Component } from 'react'
import { Link } from 'react-router-dom'
import '../../../style/legal.css'

class Privacy extends Component {
    render() {
        return (
            <div className="container">
                <div className="legal-page">
                    <h1>Privacy Policy</h1>
                    <p className="last-updated">Last updated: March 7, 2026</p>
                    
                    <section>
                        <h2>1. Information We Collect</h2>
                        <p>We collect information that you provide directly to us, including when you create an account, leave a review, or contact us. This may include your name, email address, username, and any reviews or comments you submit.</p>
                    </section>

                    <section>
                        <h2>2. How We Use Your Information</h2>
                        <p>We use the information we collect to:</p>
                        <ul>
                            <li>Provide, maintain, and improve our services</li>
                            <li>Process and display your reviews</li>
                            <li>Send you technical notices and support messages</li>
                            <li>Respond to your comments and questions</li>
                            <li>Monitor and analyze trends and usage</li>
                        </ul>
                    </section>

                    <section>
                        <h2>3. Information Sharing</h2>
                        <p>We do not sell, trade, or rent your personal information to third parties. We may share your information only in the following circumstances:</p>
                        <ul>
                            <li>With your consent</li>
                            <li>To comply with legal obligations</li>
                            <li>To protect our rights and safety</li>
                        </ul>
                    </section>

                    <section>
                        <h2>4. Data Security</h2>
                        <p>We implement appropriate security measures to protect your personal information. However, no method of transmission over the internet is 100% secure.</p>
                    </section>

                    <section>
                        <h2>5. Your Rights</h2>
                        <p>You have the right to access, update, or delete your personal information at any time through your account settings or by contacting us.</p>
                    </section>

                    <section>
                        <h2>6. Cookies</h2>
                        <p>We use cookies to enhance your experience on our site. You can choose to disable cookies through your browser settings.</p>
                    </section>

                    <section>
                        <h2>7. Changes to This Policy</h2>
                        <p>We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new policy on this page.</p>
                    </section>

                    <section>
                        <h2>8. Contact Us</h2>
                        <p>If you have any questions about this Privacy Policy, please contact us at <Link to="/legal/contact">our contact page</Link>.</p>
                    </section>
                </div>
            </div>
        )
    }
}

export default Privacy

