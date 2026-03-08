import { Component } from 'react'
import { Link } from 'react-router-dom'
import { withRouter } from '../../../services/withRouter'
import ApiRequest from '../../../services/api'
import '../../../style/legal.css'
import '../../../style/company.css'

class CompanyList extends Component {
    constructor(props) {
        super(props)
        this.state = {
            companies: [],
            loading: true
        }
    }

    componentDidMount() {
        this.loadCompanies()
    }

    loadCompanies = async () => {
        this.setState({ loading: true })
        
        try {
            const request = new ApiRequest({
                url: '/review/listCompany',
                method: 'GET',
                params: {}
            })
            
            const response = await request.exec()
            
            if (response.status === 'OK' && response.response?.companies) {
                this.setState({
                    companies: response.response.companies,
                    loading: false
                })
            } else {
                this.setState({ loading: false })
            }
        } catch (error) {
            console.error('Failed to load companies:', error)
            this.setState({ loading: false })
        }
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

    renderCompanyCard = (company) => {
        return (
            <div key={company.id} className="company-card">
                <div className="company-card-header">
                    <h2>
                        <Link to={`/review/company/${company.id}`} className="company-link">
                            {company.name}
                        </Link>
                    </h2>
                    {company.average_rating && (
                        <div className="company-rating">
                            {this.renderStars(company.average_rating)}
                            <span className="rating-text">{parseFloat(company.average_rating).toFixed(1)}</span>
                        </div>
                    )}
                </div>
                
                {company.description && (
                    <p className="company-description">{company.description}</p>
                )}
                
                <div className="company-card-footer">
                    <div className="company-stats">
                        <span className="stat-item">
                            <strong>{company.total_reviews || 0}</strong> 
                            {' '}reviews
                        </span>
                    </div>
                    <Link to={`/review/company/${company.id}`} className="btn btn-primary btn-small">
                        View Reviews
                    </Link>
                </div>
            </div>
        )
    }

    render() {
        const { companies, loading } = this.state

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
                    <h1>Companies</h1>
                    <p className="intro-text">Browse all companies and read reviews from the community</p>
                    
                    {companies.length === 0 ? (
                        <div className="empty-state">
                            <div className="empty-icon">🏢</div>
                            <h2>No Companies Found</h2>
                            <p>There are no companies available at the moment.</p>
                        </div>
                    ) : (
                        <div className="companies-grid">
                            {companies.map((company) => this.renderCompanyCard(company))}
                        </div>
                    )}
                </div>
            </div>
        )
    }
}

class CompanySingle extends Component {
    constructor(props) {
        super(props)
        this.state = {
            company: null,
            reviews: [],
            tags: [],
            average_rating: 0,
            total_reviews: 0,
            loading: true
        }
    }

    componentDidMount() {
        const { id } = this.props.params || {}
        if (id) {
            this.loadCompany(id)
        }
    }

    componentDidUpdate(prevProps) {
        const { id } = this.props.params || {}
        const prevId = prevProps.params?.id
        
        if (id && id !== prevId) {
            this.loadCompany(id)
        }
    }

    loadCompany = async (id) => {
        this.setState({ loading: true })
        
        try {
            const request = new ApiRequest({
                url: '/review/getCompany',
                method: 'POST',
                params: { id: parseInt(id) }
            })
            
            const response = await request.exec()
            
            if (response.status === 'OK' && response.response?.company) {
                this.setState({
                    company: response.response.company,
                    reviews: response.response.reviews || [],
                    tags: response.response.tags || [],
                    average_rating: response.response.average_rating || 0,
                    total_reviews: response.response.total_reviews || 0,
                    loading: false
                })
            } else {
                this.setState({ loading: false })
                if (this.props.navigate) {
                    this.props.navigate('/review/company')
                }
            }
        } catch (error) {
            console.error('Failed to load company:', error)
            this.setState({ loading: false })
            if (this.props.navigate) {
                this.props.navigate('/review/company')
            }
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

    renderLargeStars = (rating) => {
        const stars = []
        const ratingValue = parseFloat(rating) || 0
        for (let i = 1; i <= 5; i++) {
            stars.push(
                <span key={i} className={`star star-large ${i <= ratingValue ? 'filled' : ''}`}>
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

    renderCompanyHeader = (company) => {
        return (
            <div className="company-detail-header">
                <h1>{company.name}</h1>
                {company.website && (
                    <a 
                        href={company.website} 
                        target="_blank" 
                        rel="noopener noreferrer" 
                        className="company-website"
                    >
                        Visit Website →
                    </a>
                )}
            </div>
        )
    }

    renderTags = (tags) => {
        if (tags.length === 0) return null

        return (
            <div className="company-tags">
                <h3>Tags</h3>
                <div className="tags-list">
                    {tags.map((tag) => (
                        <Link 
                            key={tag.id} 
                            to={`/review/search?tag=${tag.id}`} 
                            className="tag-badge" 
                            style={{
                                backgroundColor: `${tag.color}20`,
                                color: tag.color,
                                borderColor: tag.color
                            }}
                        >
                            {tag.name}
                        </Link>
                    ))}
                </div>
            </div>
        )
    }

    renderRatingSummary = (average_rating, total_reviews) => {
        return (
            <div className="company-rating-summary">
                {total_reviews > 0 ? (
                    <div className="rating-display-large">
                        {this.renderLargeStars(average_rating)}
                        <div className="rating-info">
                            <span className="rating-value">{parseFloat(average_rating).toFixed(1)}</span>
                            <span className="rating-count">
                                Based on {total_reviews} {total_reviews === 1 ? 'review' : 'reviews'}
                            </span>
                        </div>
                    </div>
                ) : (
                    <div className="no-reviews-yet">
                        <p>No reviews yet. Be the first to review this company!</p>
                    </div>
                )}
            </div>
        )
    }

    renderReviewForm = (company) => {
        return (
            <div className="add-review-section">
                <h2>Write a Review</h2>
                <p className="form-intro">Share your experience with {company.name}</p>
                
                <form method="POST" action={`/review/company/${company.id}`} className="review-form">
                    <input type="hidden" name="company_id" value={company.id} />
                    
                    <div className="form-group">
                        <label htmlFor="rating">Rating <span className="required">*</span></label>
                        <div className="rating-select-wrapper">
                            <select id="rating" name="rating" className="rating-select" required defaultValue="">
                                <option value="" disabled>Select a rating</option>
                                <option value="5">5 - Excellent ⭐⭐⭐⭐⭐</option>
                                <option value="4">4 - Very Good ⭐⭐⭐⭐</option>
                                <option value="3">3 - Good ⭐⭐⭐</option>
                                <option value="2">2 - Fair ⭐⭐</option>
                                <option value="1">1 - Poor ⭐</option>
                            </select>
                        </div>
                    </div>
                    
                    <div className="form-group">
                        <label htmlFor="title">Review Title <span className="optional">(Optional)</span></label>
                        <input 
                            type="text" 
                            id="title" 
                            name="title" 
                            placeholder="e.g., Great service and friendly staff" 
                            className="form-input"
                        />
                    </div>
                    
                    <div className="form-group">
                        <label htmlFor="content">Your Review <span className="required">*</span></label>
                        <textarea 
                            id="content" 
                            name="content" 
                            rows="6" 
                            placeholder="Tell us about your experience. What did you like? What could be improved?" 
                            className="form-textarea" 
                            required
                        />
                        <small className="form-hint">Minimum 10 characters</small>
                    </div>
                    
                    <div className="form-actions">
                        <button type="submit" className="btn btn-primary btn-large">Submit Review</button>
                    </div>
                </form>
            </div>
        )
    }

    renderReviewsList = (reviews) => {
        if (reviews.length === 0) {
            return (
                <div className="empty-state">
                    <div className="empty-icon">📝</div>
                    <h2>No Reviews Yet</h2>
                    <p>This company doesn't have any reviews yet. Be the first to share your experience!</p>
                </div>
            )
        }

        return (
            <div className="reviews-section">
                <h2>Reviews</h2>
                <div className="reviews-list">
                    {reviews.map((review) => this.renderReview(review))}
                </div>
            </div>
        )
    }

    render() {
        const { company, reviews, tags, average_rating, total_reviews, loading } = this.state

        if (loading) {
            return (
                <div className="container">
                    <div className="legal-page">
                        <p>Loading...</p>
                    </div>
                </div>
            )
        }

        if (!company) {
            return (
                <div className="container">
                    <div className="legal-page">
                        <h1>Company Not Found</h1>
                        <Link to="/review/company" className="btn btn-outline">Back to Companies</Link>
                    </div>
                </div>
            )
        }

        return (
            <div className="container">
                <div className="legal-page">
                    {this.renderCompanyHeader(company)}
                    
                    {company.description && (
                        <p className="intro-text">
                            {company.description.split('\n').map((line, index) => (
                                <span key={index}>
                                    {line}
                                    {index < company.description.split('\n').length - 1 && <br />}
                                </span>
                            ))}
                        </p>
                    )}
                    
                    {this.renderTags(tags)}
                    {this.renderRatingSummary(average_rating, total_reviews)}
                    {this.renderReviewForm(company)}
                    {this.renderReviewsList(reviews)}
                    
                    <div style={{ marginTop: '2rem' }}>
                        <Link to="/review/company" className="btn btn-outline">Back to Companies</Link>
                    </div>
                </div>
            </div>
        )
    }
}

class Company extends Component {
    render() {
        const location = this.props.location || { pathname: window.location.pathname }
        const pathname = location.pathname
        
        const pathParts = pathname.split('/').filter(Boolean)
        const companyIndex = pathParts.indexOf('company')
        
        let id = null
        if (companyIndex !== -1 && companyIndex + 1 < pathParts.length) {
            id = pathParts[companyIndex + 1]
        }
        
        if (id) {
            return <CompanySingle {...this.props} params={{ ...this.props.params, id }} />
        }
        
        return <CompanyList {...this.props} />
    }
}

export default withRouter(Company);