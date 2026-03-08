import { Component } from 'react'
import { Link } from 'react-router-dom'
import '../../../style/legal.css'

class About extends Component {
    render() {
        return (
            <div className="container">
                <div className="legal-page">
                    <h1>About Us</h1>
                    
                    <section>
                        <h2>Our Mission</h2>
                        <p>ReviewHub was founded with a simple mission: to help people make informed decisions by providing a platform where honest reviews and experiences can be shared. We believe that transparency and community-driven insights lead to better choices for everyone.</p>
                    </section>

                    <section>
                        <h2>What We Do</h2>
                        <p>ReviewHub is a comprehensive review platform that allows users to discover, review, and share experiences about companies across various industries. Whether you're looking for a service provider, evaluating a business, or sharing your own experience, ReviewHub provides the tools and community to make it happen.</p>
                    </section>

                    <section>
                        <h2>Our Values</h2>
                        <ul>
                            <li><strong>Transparency:</strong> We believe in honest, authentic reviews that help others make informed decisions.</li>
                            <li><strong>Community:</strong> We're built by and for our community of reviewers and users.</li>
                            <li><strong>Integrity:</strong> We maintain high standards for content quality and user conduct.</li>
                            <li><strong>Accessibility:</strong> Everyone should be able to share their voice, whether signed in or anonymous.</li>
                        </ul>
                    </section>

                    <section>
                        <h2>How It Works</h2>
                        <p>Users can browse companies, read reviews, and leave their own feedback. Companies are organized by tags and categories, making it easy to find what you're looking for. Our platform supports both registered users and anonymous reviewers, ensuring everyone can participate.</p>
                    </section>

                    <section>
                        <h2>Join Our Community</h2>
                        <p>Whether you're a consumer looking for reliable businesses or someone who wants to share your experiences, ReviewHub welcomes you. <Link to="/user/register">Create an account</Link> to get started, or browse reviews as a guest.</p>
                    </section>

                    <section>
                        <h2>Contact Us</h2>
                        <p>Have questions or feedback? We'd love to hear from you! Visit our <Link to="/legal/contact">contact page</Link> to get in touch.</p>
                    </section>
                </div>
            </div>
        )
    }
}

export default About

