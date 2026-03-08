import { Component } from 'react'
import { Link } from 'react-router-dom'
import '../../../style/legal.css'

class Terms extends Component {
    render() {
        return (
            <div className="container">
                <div className="legal-page">
                    <h1>Terms of Service</h1>
                    <p className="last-updated">Last updated: March 7, 2026</p>
                    
                    <section>
                        <h2>1. Acceptance of Terms</h2>
                        <p>By accessing and using ReviewHub, you accept and agree to be bound by the terms and provision of this agreement.</p>
                    </section>

                    <section>
                        <h2>2. Use License</h2>
                        <p>Permission is granted to temporarily access the materials on ReviewHub for personal, non-commercial transitory viewing only. This is the grant of a license, not a transfer of title.</p>
                    </section>

                    <section>
                        <h2>3. User Accounts</h2>
                        <p>When you create an account with us, you must provide accurate and complete information. You are responsible for maintaining the security of your account and password.</p>
                    </section>

                    <section>
                        <h2>4. User Conduct</h2>
                        <p>You agree not to:</p>
                        <ul>
                            <li>Post false, misleading, or defamatory content</li>
                            <li>Violate any laws or regulations</li>
                            <li>Infringe on the rights of others</li>
                            <li>Spam or harass other users</li>
                            <li>Impersonate any person or entity</li>
                        </ul>
                    </section>

                    <section>
                        <h2>5. Reviews and Content</h2>
                        <p>You retain ownership of any content you post, but grant us a license to use, display, and distribute your content on our platform. Reviews must be honest and based on actual experiences.</p>
                    </section>

                    <section>
                        <h2>6. Intellectual Property</h2>
                        <p>The content on ReviewHub, including text, graphics, logos, and software, is the property of ReviewHub or its content suppliers and is protected by copyright and trademark laws.</p>
                    </section>

                    <section>
                        <h2>7. Disclaimer</h2>
                        <p>The materials on ReviewHub are provided on an 'as is' basis. ReviewHub makes no warranties, expressed or implied, and hereby disclaims all other warranties.</p>
                    </section>

                    <section>
                        <h2>8. Limitations</h2>
                        <p>In no event shall ReviewHub or its suppliers be liable for any damages arising out of the use or inability to use the materials on ReviewHub.</p>
                    </section>

                    <section>
                        <h2>9. Modifications</h2>
                        <p>ReviewHub may revise these terms of service at any time without notice. By using this website, you are agreeing to be bound by the then current version of these terms.</p>
                    </section>

                    <section>
                        <h2>10. Contact Information</h2>
                        <p>If you have any questions about these Terms of Service, please contact us at <Link to="/legal/contact">our contact page</Link>.</p>
                    </section>
                </div>
            </div>
        )
    }
}

export default Terms

