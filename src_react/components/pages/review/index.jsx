import { Component } from 'react'
import { Link } from 'react-router-dom'
import { withRouter } from '../../../services/withRouter'
import ApiRequest from '../../../services/api'
import '../../../style/legal.css'
import '../../../style/company.css'

class Review extends Component {
    constructor(props) {
        super(props)
        this.state = {
            reviewsByCompany: [],
            loading: true
        }
    }

    componentDidMount() {
        this.loadReviews()
    }

    loadReviews = async () => {
        this.setState({ loading: true })
        
        try {
            const request = new ApiRequest({
                url: '/review/getReview',
                method: 'GET',
                params: {}
            })
            
            const response = await request.exec()
            
            if (response.status === 'OK' && response.response?.reviewsByCompany) {
                this.setState({
                    reviewsByCompany: response.response.reviewsByCompany,
                    loading: false
                })
            } else {
                this.setState({ loading: false })
            }
        } catch (error) {
            console.error('Failed to load reviews:', error)
            this.setState({ loading: false })
        }
    }

    formatDate = (dateString) => {
        if (!dateString) return 'N/A'
        const date = new Date(dateString)
        return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })
    }

    renderStars = (rating) => {
        const stars = []
        const ratingValue = parseFloat(rating) || 0
        for (let i = 1; i <= 5; i++) {
            stars.push(
                <span key={i} className={`star ${i <= ratingValue ? 'filled' : ''}`}>
                    ★
                </span>
            )
        }
        return stars
    }

    renderReview = (review) => {
        return (
            <div key={review.id} className="review-card">
                <div className="review-header">
                    <div className="review-rating">
                        {this.renderStars(review.rating)}
                    </div>
                    <div className="review-meta">
                        <span className="review-author">
                            {review.user ? `By ${review.user.username}` : 'Anonymous'}
                        </span>
                        <span className="review-date">
                            {this.formatDate(review.created_at)}
                        </span>
                    </div>
                </div>
                
                {review.title && (
                    <h3 className="review-title">{review.title}</h3>
                )}
                
                <p className="review-content">
                    {review.content?.split('\n').map((line, index) => (
                        <span key={index}>
                            {line}
                            {index < review.content.split('\n').length - 1 && <br />}
                        </span>
                    ))}
                </p>
            </div>
        )
    }

    renderCompanySection = (companyData) => {
        const { company, reviews, average_rating, total_reviews } = companyData
        
        return (
            <div key={company.id} className="company-reviews-section">
                <div className="company-header">
                    <h2>
                        <Link to={`/review/company/${company.id}`} className="company-link">
                            {company.name}
                        </Link>
                    </h2>
                    <div className="company-stats">
                        <div className="rating-display">
                            {this.renderStars(average_rating)}
                            <span className="rating-text">
                                {parseFloat(average_rating).toFixed(1)} ({total_reviews} reviews)
                            </span>
                        </div>
                    </div>
                </div>
                
                <div className="reviews-list">
                    {reviews.map((review) => this.renderReview(review))}
                </div>
            </div>
        )
    }

    render() {
        const { reviewsByCompany, loading } = this.state

        if (loading) {
            return (
                <div className="container">
                    <div className="legal-page">
                        <p>Loading...</p>
                    </div>
                </div>
            )
        }

        return (
            <div className="container">
                <div className="legal-page">
                    <h1>All Reviews</h1>
                    <p className="intro-text">Browse reviews grouped by company</p>
                    
                    {reviewsByCompany.length === 0 ? (
                        <div className="empty-state">
                            <div className="empty-icon">📝</div>
                            <h2>No Reviews Yet</h2>
                            <p>There are no reviews available yet. Be the first to review a company!</p>
                            <Link to="/review/company" className="btn btn-primary">Browse Companies</Link>
                        </div>
                    ) : (
                        <div className="companies-reviews-list">
                            {reviewsByCompany.map((companyData) => this.renderCompanySection(companyData))}
                        </div>
                    )}
                </div>
            </div>
        )
    }
}

export default withRouter(Review);