import { Component } from 'react'
import { Link } from 'react-router-dom'
import { withRouter } from '../../../services/withRouter'
import ApiRequest from '../../../services/api'
import '../../../style/admin.css'

class Tags extends Component {
    constructor(props) {
        super(props)
        this.state = {
            tags: [],
            loading: true,
            success: null,
            error: null
        }
    }

    componentDidMount() {
        this.loadTags()
    }

    loadTags = async () => {
        this.setState({ loading: true, success: null, error: null })

        try {
            const request = new ApiRequest({
                url: '/review/getTags',
                method: 'GET',
                params: {}
            })

            const response = await request.exec()

            if (response.status === 'OK' && response.response?.tags) {
                const tagsWithCounts = await Promise.all(
                    response.response.tags.map(async (tag) => {
                        try {
                            const companiesRequest = new ApiRequest({
                                url: '/review/searchByTag',
                                method: 'POST',
                                params: { tag_id: tag.id }
                            })
                            const companiesResponse = await companiesRequest.exec()
                            const companyCount = companiesResponse.status === 'OK' && companiesResponse.response?.companies
                                ? companiesResponse.response.companies.length
                                : 0
                            return { ...tag, company_count: companyCount }
                        } catch (error) {
                            return { ...tag, company_count: 0 }
                        }
                    })
                )

                this.setState({
                    tags: tagsWithCounts,
                    loading: false
                })
            } else {
                this.setState({ loading: false })
            }
        } catch (error) {
            console.error('Failed to load tags:', error)
            this.setState({ loading: false })
        }
    }

    handleDelete = async (tagId) => {
        if (!window.confirm('Are you sure you want to delete this tag?')) {
            return
        }

        try {
            const request = new ApiRequest({
                url: '/review/deleteTag',
                method: 'POST',
                params: { id: tagId }
            })

            const response = await request.exec()

            if (response.status === 'OK') {
                this.setState({ success: 'Tag deleted successfully', error: null })
                this.loadTags()
            } else {
                this.setState({ error: response.response?.error || 'Failed to delete tag', success: null })
            }
        } catch (error) {
            console.error('Failed to delete tag:', error)
            this.setState({ error: 'Failed to delete tag', success: null })
        }
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

    renderTagCard = (tag) => {
        return (
            <div key={tag.id} className="admin-tag-card">
                <div 
                    className="admin-tag-header" 
                    style={{
                        backgroundColor: `${tag.color}20`,
                        borderLeft: `4px solid ${tag.color}`
                    }}
                >
                    <h3 style={{ color: tag.color }}>{tag.name}</h3>
                </div>
                <div className="admin-tag-content">
                    {tag.description && (
                        <p>{tag.description}</p>
                    )}
                    <div className="admin-tag-stats">
                        <span><strong>{tag.company_count || 0}</strong> companies</span>
                    </div>
                </div>
                <div className="admin-tag-actions">
                    <Link to={`/admin/tag/edit/${tag.id}`} className="btn btn-small btn-outline">
                        Edit
                    </Link>
                    <button
                        onClick={() => this.handleDelete(tag.id)}
                        className="btn btn-small btn-danger"
                    >
                        Delete
                    </button>
                </div>
            </div>
        )
    }

    renderTagsGrid = (tags) => {
        return (
            <div className="admin-tags-grid">
                {tags.map((tag) => this.renderTagCard(tag))}
            </div>
        )
    }

    renderEmptyState = () => {
        return (
            <div className="empty-state">
                <div className="empty-icon">🏷️</div>
                <h2>No Tags</h2>
                <p>Get started by creating your first tag.</p>
                <Link to="/admin/tag/create" className="btn btn-primary">Create Tag</Link>
            </div>
        )
    }

    render() {
        const { tags, loading, success, error } = this.state

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
                        <h1>Manage Tags</h1>
                        <Link to="/admin/tag/create" className="btn btn-primary">Create Tag</Link>
                    </div>
                    {this.renderAlerts(success, error)}
                    {tags.length === 0 ? this.renderEmptyState() : this.renderTagsGrid(tags)}
                    <div style={{ marginTop: '2rem' }}>
                        <Link to="/admin" className="btn btn-outline">Back to Dashboard</Link>
                    </div>
                </div>
            </div>
        )
    }
}

export default withRouter(Tags);

