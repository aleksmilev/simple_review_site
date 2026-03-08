import { Component } from 'react'
import { Link } from 'react-router-dom'
import '../../../style/home.css'

class Home extends Component {
    render() {
        return (
            <>
                <div className="home-hero">
                    <div className="container">
                        <div className="hero-content">
                            <h1 className="hero-title">Find and Review Companies</h1>
                            <p className="hero-subtitle">Discover trusted businesses and share your experiences. Help others make informed decisions.</p>
                            <div className="hero-actions">
                                <Link to="/review/company" className="btn btn-primary btn-large">Browse Companies</Link>
                                <Link to="/user/register" className="btn btn-outline btn-large">Join Now</Link>
                            </div>
                        </div>
                    </div>
                </div>

                <div className="container">
                    <section className="features-section">
                        <h2 className="section-title">Why ReviewHub?</h2>
                        <div className="features-grid">
                            <div className="feature-card">
                                <div className="feature-icon">⭐</div>
                                <h3>Honest Reviews</h3>
                                <p>Read authentic reviews from real customers. Share your experiences and help others.</p>
                            </div>
                            <div className="feature-card">
                                <div className="feature-icon">🏢</div>
                                <h3>Discover Companies</h3>
                                <p>Explore businesses across various industries. Find the perfect company for your needs.</p>
                            </div>
                            <div className="feature-card">
                                <div className="feature-icon">🏷️</div>
                                <h3>Organized by Tags</h3>
                                <p>Browse companies by categories and tags. Find exactly what you're looking for.</p>
                            </div>
                            <div className="feature-card">
                                <div className="feature-icon">👥</div>
                                <h3>Community Driven</h3>
                                <p>Join a community of reviewers. Your voice matters and helps others make better choices.</p>
                            </div>
                        </div>
                    </section>

                    <section className="cta-section">
                        <div className="cta-content">
                            <h2>Ready to get started?</h2>
                            <p>Join thousands of users sharing their experiences and discovering great companies.</p>
                            <Link to="/user/register" className="btn btn-primary btn-large">Create Account</Link>
                        </div>
                    </section>
                </div>
            </>
        )
    }
}

export default Home

