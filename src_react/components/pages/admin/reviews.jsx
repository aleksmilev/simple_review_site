import { Component } from 'react'
import { Link } from 'react-router-dom'
import { withRouter } from '../../../services/withRouter'
import ApiRequest from '../../../services/api'
import '../../../style/admin.css'
import '../../../style/company.css'

class Reviews extends Component {
    constructor(props) {
        super(props)
        this.state = {
            reviews: [],
            loading: true,
            success: null,
            error: null
        }
    }

    componentDidMount() {
        this.loadReviews()
    }

    loadReviews = async () => {
        this.setState({ loading: true, success: null, error: null })

        try {
            const request = new ApiRequest({
                url: '/review/getReview',
                method: 'GET',
                params: {}
            })

            const response = await request.exec()

            if (response.status === 'OK' && response.response?.reviewsByCompany) {
                const allReviews = []
                response.response.reviewsByCompany.forEach(companyData => {
                    if (companyData.reviews && companyData.company) {
                        companyData.reviews.forEach(review => {
                            allReviews.push({
                                ...review,
                                company: companyData.company
                            })
                        })
                    }
                })
                
                allReviews.sort((a, b) => {
                    const dateA = new Date(a.created_at || 0)
                    const dateB = new Date(b.created_at || 0)
                    return dateB - dateA
                })

                this.setState({
                    reviews: allReviews,
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

    handleDelete = async (reviewId) => {
        if (!window.confirm('Are you sure you want to delete this review?')) {
            return
        }

        try {
            const request = new ApiRequest({
                url: '/review/deleteReview',
                method: 'POST',
                params: { id: reviewId }
            })

            const response = await request.exec()

            if (response.status === 'OK') {
                this.setState({ success: 'Review deleted successfully', error: null })
                this.loadReviews()
            } else {
                this.setState({ error: response.response?.error || 'Failed to delete review', success: null })
            }
        } catch (error) {
            console.error('Failed to delete review:', error)
            this.setState({ error: 'Failed to delete review', success: null })
        }
    }

    formatDate = (dateString) => {
        if (!dateString) return 'N/A'
        const date = new Date(dateString)
        return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
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
            <div key={review.id} className="admin-review-card">
                <div className="admin-review-header">
                    <div className="admin-review-rating">
                        {this.renderStars(review.rating)}
                    </div>
                    <div className="admin-review-meta">
                        <span>
                            <strong>Company:</strong>{' '}
                            <Link to={`/review/company/${review.company_id}`}>
                                {review.company?.name || 'Unknown'}
                            </Link>
                        </span>
                        <span>
                            <strong>By:</strong>{' '}
                            {review.user ? review.user.username : 'Anonymous'}
                        </span>
                        <span>
                            <strong>Date:</strong> {this.formatDate(review.created_at)}
                        </span>
                    </div>
                </div>

                {review.title && (
                    <h3 className="admin-review-title">{review.title}</h3>
                )}

                <p className="admin-review-content">
                    {review.content?.split('\n').map((line, index) => (
                        <span key={index}>
                            {line}
                            {index < review.content.split('\n').length - 1 && <br />}
                        </span>
                    ))}
                </p>

                <div className="admin-review-actions">
                    <button
                        onClick={() => this.handleDelete(review.id)}
                        className="btn btn-small btn-danger"
                    >
                        Delete Review
                    </button>
                </div>
            </div>
        )
    }

    renderAlerts = (success, error) => {
        return (
            <>
                {success && (
                    <div className="alert alert-success">
                        {success}
                    </div>
                )}

                {error && (
                    <div className="alert alert-error">
                        {error}
                    </div>
                )}
            </>
        )
    }

    renderReviewsList = (reviews) => {
        if (reviews.length === 0) {
            return (
                <div className="empty-state">
                    <div className="empty-icon">📝</div>
                    <h2>No Reviews</h2>
                    <p>No reviews found.</p>
                </div>
            )
        }

        return (
            <div className="admin-reviews-list">
                {reviews.map((review) => this.renderReview(review))}
            </div>
        )
    }

    render() {
        const { reviews, loading, success, error } = this.state

        if (loading) {
            return (
                <div className="container">
                    <div className="admin-page">
                        <p>Loading...</p>
                    </div>
                </div>
            )
        }

        return (
            <div className="container">
                <div className="admin-page">
                    <h1>Manage Reviews</h1>
                    {this.renderAlerts(success, error)}
                    {this.renderReviewsList(reviews)}
                    <div style={{ marginTop: '2rem' }}>
                        <Link to="/admin" className="btn btn-outline">Back to Dashboard</Link>
                    </div>
                </div>
            </div>
        )
    }
}

export default withRouter(Reviews);

