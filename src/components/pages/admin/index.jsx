import { Component } from 'react'
import { Link } from 'react-router-dom'
import { withRouter } from '../../../services/withRouter'
import ApiRequest from '../../../services/api'
import '../../../style/admin.css'

class Admin extends Component {
    constructor(props) {
        super(props)
        this.state = {
            totalCompanies: 0,
            totalReviews: 0,
            totalUsers: 0,
            totalTags: 0,
            recentFeedback: [],
            loading: true
        }
    }

    componentDidMount() {
        this.loadDashboardData()
    }

    loadDashboardData = async () => {
        this.setState({ loading: true })

        try {
            const companiesRequest = new ApiRequest({
                url: '/review/listCompany',
                method: 'GET',
                params: {}
            })

            const reviewsRequest = new ApiRequest({
                url: '/review/getReview',
                method: 'GET',
                params: {}
            })

            const tagsRequest = new ApiRequest({
                url: '/review/getTags',
                method: 'GET',
                params: {}
            })

            const usersRequest = new ApiRequest({
                url: '/user/getAllUsers',
                method: 'GET',
                params: {}
            })

            const feedbackRequest = new ApiRequest({
                url: '/legal/list',
                method: 'GET',
                params: {}
            })

            const [companiesResponse, reviewsResponse, tagsResponse, usersResponse, feedbackResponse] = await Promise.allSettled([
                companiesRequest.exec(),
                reviewsRequest.exec(),
                tagsRequest.exec(),
                usersRequest.exec(),
                feedbackRequest.exec()
            ])

            let totalCompanies = 0
            let totalReviews = 0
            let totalTags = 0
            let totalUsers = 0
            let recentFeedback = []

            if (companiesResponse.status === 'fulfilled' && companiesResponse.value.status === 'OK' && companiesResponse.value.response?.companies) {
                totalCompanies = companiesResponse.value.response.companies.length
            }
            
            if (reviewsResponse.status === 'fulfilled' && reviewsResponse.value.status === 'OK' && reviewsResponse.value.response?.reviewsByCompany) {
                totalReviews = reviewsResponse.value.response.reviewsByCompany.reduce((sum, company) => sum + (company.reviews?.length || 0), 0)
            }
            
            if (tagsResponse.status === 'fulfilled' && tagsResponse.value.status === 'OK' && tagsResponse.value.response?.tags) {
                totalTags = tagsResponse.value.response.tags.length
            }

            if (usersResponse.status === 'fulfilled' && usersResponse.value.status === 'OK' && usersResponse.value.response?.users) {
                totalUsers = usersResponse.value.response.users.length
            }

            if (feedbackResponse.status === 'fulfilled' && feedbackResponse.value.status === 'OK' && feedbackResponse.value.response?.data) {
                recentFeedback = feedbackResponse.value.response.data.slice(0, 5)
            }

            this.setState({
                totalCompanies,
                totalReviews,
                totalUsers,
                totalTags,
                recentFeedback,
                loading: false
            })
        } catch (error) {
            console.error('Failed to load dashboard data:', error)
            this.setState({ loading: false })
        }
    }

    formatDate = (dateString) => {
        if (!dateString) return 'N/A'
        const date = new Date(dateString)
        return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
    }

    renderStatCard = (icon, value, label, link) => {
        return (
            <div key={label} className="admin-stat-card">
                <div className="stat-icon">{icon}</div>
                <div className="stat-content">
                    <div className="stat-value">{value}</div>
                    <div className="stat-label">{label}</div>
                </div>
                <Link to={link} className="stat-link">Manage →</Link>
            </div>
        )
    }

    renderFeedbackItem = (item) => {
        return (
            <div key={item.id} className="feedback-item">
                <div className="feedback-header">
                    <strong>{item.name}</strong>
                    <span className="feedback-email">{item.email}</span>
                    <span className="feedback-date">{this.formatDate(item.created_at)}</span>
                </div>
                <div className="feedback-subject">{item.subject}</div>
                <div className="feedback-message">
                    {item.message?.split('\n').map((line, index) => (
                        <span key={index}>
                            {line}
                            {index < item.message.split('\n').length - 1 && <br />}
                        </span>
                    ))}
                </div>
            </div>
        )
    }

    render() {
        const { totalCompanies, totalReviews, totalUsers, totalTags, recentFeedback, loading } = this.state

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
                    <h1>Admin Dashboard</h1>
                    
                    <div className="admin-stats-grid">
                        {this.renderStatCard('🏢', totalCompanies, 'Companies', '/admin/companies')}
                        {this.renderStatCard('📝', totalReviews, 'Reviews', '/admin/reviews')}
                        {this.renderStatCard('👥', totalUsers, 'Users', '/admin/users')}
                        {this.renderStatCard('🏷️', totalTags, 'Tags', '/admin/tags')}
                    </div>
                    
                    <div className="admin-quick-actions">
                        <h2>Quick Actions</h2>
                        <div className="quick-actions-grid">
                            <Link to="/admin/company/create" className="quick-action-card">
                                <div className="quick-action-icon">➕</div>
                                <div className="quick-action-label">Create Company</div>
                            </Link>
                            <Link to="/admin/tag/create" className="quick-action-card">
                                <div className="quick-action-icon">🏷️</div>
                                <div className="quick-action-label">Create Tag</div>
                            </Link>
                        </div>
                    </div>
                    
                    <div className="admin-section">
                        <div className="admin-section-header">
                            <h2>Recent Feedback</h2>
                            <Link to="/admin/feedback" className="btn btn-outline">View All</Link>
                        </div>
                        
                        {recentFeedback.length === 0 ? (
                            <div className="empty-state">
                                <p>No feedback yet.</p>
                            </div>
                        ) : (
                            <div className="feedback-list">
                                {recentFeedback.map((item) => this.renderFeedbackItem(item))}
                            </div>
                        )}
                    </div>
                </div>
            </div>
        )
    }
}

export default withRouter(Admin);

