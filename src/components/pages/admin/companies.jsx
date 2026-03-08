import { Component } from 'react'
import { Link } from 'react-router-dom'
import { withRouter } from '../../../services/withRouter'
import ApiRequest from '../../../services/api'
import '../../../style/admin.css'
import '../../../style/company.css'

class Companies extends Component {
    constructor(props) {
        super(props)
        this.state = {
            companies: [],
            loading: true,
            success: null,
            error: null
        }
    }

    componentDidMount() {
        this.loadCompanies()
    }

    loadCompanies = async () => {
        this.setState({ loading: true, success: null, error: null })

        try {
            const request = new ApiRequest({
                url: '/review/listCompany',
                method: 'GET',
                params: {}
            })

            const response = await request.exec()

            if (response.status === 'OK' && response.response?.companies) {
                const companies = response.response.companies.sort((a, b) => {
                    const dateA = new Date(a.created_at || 0)
                    const dateB = new Date(b.created_at || 0)
                    return dateB - dateA
                })

                this.setState({
                    companies: companies,
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

    handleDelete = async (companyId) => {
        if (!window.confirm('Are you sure you want to delete this company?')) {
            return
        }

        try {
            const request = new ApiRequest({
                url: '/review/deleteCompany',
                method: 'POST',
                params: { id: companyId }
            })

            const response = await request.exec()

            if (response.status === 'OK') {
                this.setState({ success: 'Company deleted successfully', error: null })
                this.loadCompanies()
            } else {
                this.setState({ error: response.response?.error || 'Failed to delete company', success: null })
            }
        } catch (error) {
            console.error('Failed to delete company:', error)
            this.setState({ error: 'Failed to delete company', success: null })
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

    renderCompanyRow = (company) => {
        return (
            <tr key={company.id}>
                <td>
                    <strong>{company.name}</strong>
                    {company.website && (
                        <>
                            <br />
                            <small>
                                <a href={company.website} target="_blank" rel="noopener noreferrer">
                                    {company.website}
                                </a>
                            </small>
                        </>
                    )}
                </td>
                <td>
                    {company.description 
                        ? (company.description.length > 100 
                            ? `${company.description.substring(0, 100)}...` 
                            : company.description)
                        : ''}
                </td>
                <td>
                    {company.total_reviews > 0 ? (
                        <>
                            {this.renderStars(company.average_rating)}
                            <span className="rating-text">
                                {parseFloat(company.average_rating).toFixed(1)}
                            </span>
                        </>
                    ) : (
                        <span className="text-muted">No reviews</span>
                    )}
                </td>
                <td>{company.total_reviews || 0}</td>
                <td>{this.formatDate(company.created_at)}</td>
                <td>
                    <div className="admin-actions">
                        <Link to={`/review/company/${company.id}`} className="btn btn-small btn-outline">
                            View
                        </Link>
                        <Link to={`/admin/company/edit/${company.id}`} className="btn btn-small btn-outline">
                            Edit
                        </Link>
                        <button
                            onClick={() => this.handleDelete(company.id)}
                            className="btn btn-small btn-danger"
                        >
                            Delete
                        </button>
                    </div>
                </td>
            </tr>
        )
    }

    renderCompaniesTable = (companies) => {
        return (
            <div className="admin-table-container">
                <table className="admin-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Rating</th>
                            <th>Reviews</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {companies.map((company) => this.renderCompanyRow(company))}
                    </tbody>
                </table>
            </div>
        )
    }

    renderEmptyState = () => {
        return (
            <div className="empty-state">
                <div className="empty-icon">🏢</div>
                <h2>No Companies</h2>
                <p>Get started by creating your first company.</p>
                <Link to="/admin/company/create" className="btn btn-primary">Create Company</Link>
            </div>
        )
    }

    render() {
        const { companies, loading, success, error } = this.state

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
                    <div className="admin-header">
                        <h1>Manage Companies</h1>
                        <Link to="/admin/company/create" className="btn btn-primary">Create Company</Link>
                    </div>
                    {this.renderAlerts(success, error)}
                    {companies.length === 0 ? this.renderEmptyState() : this.renderCompaniesTable(companies)}
                    <div style={{ marginTop: '2rem' }}>
                        <Link to="/admin" className="btn btn-outline">Back to Dashboard</Link>
                    </div>
                </div>
            </div>
        )
    }
}

export default withRouter(Companies);
