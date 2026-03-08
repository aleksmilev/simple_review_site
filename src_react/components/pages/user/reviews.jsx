import { Component } from 'react'
import { Link } from 'react-router-dom'
import UserService from '../../../services/user'
import ApiRequest from '../../../services/api'
import { withRouter } from '../../../services/withRouter'
import '../../../style/legal.css'
import '../../../style/reviews.css'

class Reviews extends Component {
    constructor(props) {
        super(props)
        this.state = {
            reviews: [],
            loading: true
        }
    }

    componentDidMount() {
        if (!UserService.isLoggedIn()) {
            if (this.props.navigate) {
                this.props.navigate('/user/login')
            }
            return
        }
        this.loadReviews()
    }

    loadReviews = async () => {
        this.setState({ loading: true })
        
        try {
            const request = new ApiRequest({
                url: '/user/reviews',
                method: 'GET',
                params: {}
            })
            
            const response = await request.exec()
            
            if (response.status === 'OK' && response.response?.reviews) {
                this.setState({
                    reviews: response.response.reviews,
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
        for (let i = 1; i <= 5; i++) {
            stars.push(
                <span key={i} className={`star ${i <= rating ? 'filled' : ''}`}>
                    ★
                </span>
            )
        }
        return stars
    }

    render() {
        const { reviews, loading } = this.state

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
                    <h1>My Reviews</h1>
                    <p className="intro-text">All reviews you have submitted on ReviewHub</p>
                    
                    {reviews.length === 0 ? (
                        <div className="empty-state">
                            <div className="empty-icon">📝</div>
                            <h2>No Reviews Yet</h2>
                            <p>You haven't submitted any reviews yet. Start reviewing companies to help others make informed decisions!</p>
                            <Link to="/review/company" className="btn btn-primary">Browse Companies</Link>
                        </div>
                    ) : (
                        <div className="reviews-list">
                            {reviews.map((review) => (
                                <div key={review.id} className="review-card">
                                    <div className="review-header">
                                        <div className="review-rating">
                                            {this.renderStars(review.rating)}
                                        </div>
                                        <div className="review-date">
                                            {this.formatDate(review.created_at)}
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
                                    
                                    <div className="review-footer">
                                        <span className="review-company">
                                            Company ID: {review.company_id}
                                        </span>
                                    </div>
                                </div>
                            ))}
                        </div>
                    )}
                    
                    <div style={{ marginTop: '2rem' }}>
                        <Link to="/user/profile" className="btn btn-outline">Back to Profile</Link>
                        <Link to="/review/company" className="btn btn-primary" style={{ marginLeft: '1rem' }}>Browse Companies</Link>
                    </div>
                </div>
            </div>
        )
    }
}

export default withRouter(Reviews)

