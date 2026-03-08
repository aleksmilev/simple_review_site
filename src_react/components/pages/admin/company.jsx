import { Component } from 'react'
import { Link } from 'react-router-dom'
import { withRouter } from '../../../services/withRouter'
import ApiRequest from '../../../services/api'
import NotFound from '../../common/NotFound'
import '../../../style/admin.css'
import '../../../style/company.css'

class Company extends Component {
    getPathParts = () => {
        const location = this.props.location || {}
        const pathname = location.pathname || window.location.pathname
        const parts = pathname.split('/').filter(Boolean)
        return parts
    }

    render() {
        const parts = this.getPathParts()

        switch (parts[2]) {
            case 'create':
                return <Create navigate={this.props.navigate} />
            case 'edit':
                return <Edit id={parts[3]} navigate={this.props.navigate} />
            default:
                return <NotFound />
        }
    }
}

class Edit extends Component {
    constructor(props) {
        super(props)
        this.state = {
            company: null,
            tags: [],
            selectedTagIds: [],
            name: '',
            slug: '',
            description: '',
            website: '',
            loading: true,
            errors: [],
            success: null
        }
    }

    componentDidMount() {
        const { id } = this.props
        if (id) {
            this.loadCompany(id)
            this.loadTags()
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
                const company = response.response.company
                const selectedTagIds = response.response.tags?.map(tag => tag.id) || []
                
                this.setState({
                    company: company,
                    name: company.name || '',
                    slug: company.slug || '',
                    description: company.description || '',
                    website: company.website || '',
                    selectedTagIds: selectedTagIds,
                    loading: false
                })
            } else {
                this.setState({ loading: false })
                if (this.props.navigate) {
                    this.props.navigate('/admin/companies')
                }
            }
        } catch (error) {
            console.error('Failed to load company:', error)
            this.setState({ loading: false })
            if (this.props.navigate) {
                this.props.navigate('/admin/companies')
            }
        }
    }

    loadTags = async () => {
        try {
            const request = new ApiRequest({
                url: '/review/getTags',
                method: 'GET',
                params: {}
            })

            const response = await request.exec()

            if (response.status === 'OK' && response.response?.tags) {
                this.setState({ tags: response.response.tags })
            }
        } catch (error) {
            console.error('Failed to load tags:', error)
        }
    }

    handleSubmit = async (e) => {
        e.preventDefault()
        const { id } = this.props
        const { name, slug, description, website, selectedTagIds } = this.state

        const errors = []
        if (!name.trim()) {
            errors.push('Company name is required')
        }

        if (errors.length > 0) {
            this.setState({ errors, success: null })
            return
        }

        try {
            const request = new ApiRequest({
                url: '/review/updateCompany',
                method: 'POST',
                params: {
                    id: parseInt(id),
                    name: name.trim(),
                    slug: slug.trim(),
                    description: description.trim(),
                    website: website.trim(),
                    tags: selectedTagIds
                }
            })

            const response = await request.exec()

            if (response.status === 'OK') {
                if (this.props.navigate) {
                    this.props.navigate('/admin/companies')
                }
            } else {
                this.setState({ 
                    errors: [response.response?.error || 'Failed to update company'],
                    success: null
                })
            }
        } catch (error) {
            console.error('Failed to update company:', error)
            this.setState({ 
                errors: ['Failed to update company'],
                success: null
            })
        }
    }

    handleTagToggle = (tagId) => {
        const { selectedTagIds } = this.state
        const newSelectedTagIds = selectedTagIds.includes(tagId)
            ? selectedTagIds.filter(id => id !== tagId)
            : [...selectedTagIds, tagId]
        
        this.setState({ selectedTagIds: newSelectedTagIds })
    }

    handleNameChange = (e) => {
        const name = e.target.value
        this.setState({ name })
        
        if (!this.state.slug) {
            const slug = name.toLowerCase().replace(/[^a-zA-Z0-9]+/g, '-').replace(/^-+|-+$/g, '')
            this.setState({ slug })
        }
    }

    render() {
        const { id } = this.props
        const { company, tags, selectedTagIds, name, slug, description, website, loading, errors } = this.state

        if (!id) {
            return <NotFound />
        }

        if (loading) {
            return (
                <div className="container">
                    <div className="admin-page">
                        <p>Loading...</p>
                    </div>
                </div>
            )
        }

        if (!company) {
            return <NotFound />
        }

        return (
            <div className="container">
                <div className="admin-page">
                    <h1>Edit Company</h1>
                    
                    {errors.length > 0 && (
                        <div className="alert alert-error">
                            <ul style={{ margin: 0, paddingLeft: '1.25rem' }}>
                                {errors.map((error, index) => (
                                    <li key={index}>{error}</li>
                                ))}
                            </ul>
                        </div>
                    )}

                    <div className="admin-form-card">
                        <form onSubmit={this.handleSubmit}>
                            <div className="form-group">
                                <label htmlFor="name">Company Name *</label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    className="form-input"
                                    value={name}
                                    onChange={this.handleNameChange}
                                    required
                                />
                            </div>

                            <div className="form-group">
                                <label htmlFor="slug">Slug</label>
                                <input
                                    type="text"
                                    id="slug"
                                    name="slug"
                                    className="form-input"
                                    value={slug}
                                    onChange={(e) => this.setState({ slug: e.target.value })}
                                    placeholder="auto-generated if empty"
                                />
                                <small className="form-hint">URL-friendly identifier (e.g., company-name)</small>
                            </div>

                            <div className="form-group">
                                <label htmlFor="description">Description</label>
                                <textarea
                                    id="description"
                                    name="description"
                                    className="form-textarea"
                                    rows="5"
                                    value={description}
                                    onChange={(e) => this.setState({ description: e.target.value })}
                                />
                            </div>

                            <div className="form-group">
                                <label htmlFor="website">Website URL</label>
                                <input
                                    type="url"
                                    id="website"
                                    name="website"
                                    className="form-input"
                                    value={website}
                                    onChange={(e) => this.setState({ website: e.target.value })}
                                    placeholder="https://example.com"
                                />
                            </div>

                            <div className="form-group">
                                <label>Tags</label>
                                <div className="tags-checkbox-list">
                                    {tags.map((tag) => (
                                        <label key={tag.id} className="tag-checkbox">
                                            <input
                                                type="checkbox"
                                                checked={selectedTagIds.includes(tag.id)}
                                                onChange={() => this.handleTagToggle(tag.id)}
                                            />
                                            <span
                                                className="tag-badge"
                                                style={{
                                                    backgroundColor: `${tag.color}20`,
                                                    color: tag.color,
                                                    borderColor: tag.color
                                                }}
                                            >
                                                {tag.name}
                                            </span>
                                        </label>
                                    ))}
                                </div>
                            </div>

                            <div className="form-actions">
                                <button type="submit" className="btn btn-primary btn-large">
                                    Update Company
                                </button>
                                <Link to="/admin/companies" className="btn btn-outline btn-large">
                                    Cancel
                                </Link>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        )
    }
}

class Create extends Component {
    constructor(props) {
        super(props)
        this.state = {
            tags: [],
            selectedTagIds: [],
            name: '',
            slug: '',
            description: '',
            website: '',
            loading: false,
            errors: [],
            success: null
        }
    }

    componentDidMount() {
        this.loadTags()
    }

    loadTags = async () => {
        try {
            const request = new ApiRequest({
                url: '/review/getTags',
                method: 'GET',
                params: {}
            })

            const response = await request.exec()

            if (response.status === 'OK' && response.response?.tags) {
                this.setState({ tags: response.response.tags })
            }
        } catch (error) {
            console.error('Failed to load tags:', error)
        }
    }

    handleSubmit = async (e) => {
        e.preventDefault()
        const { name, slug, description, website, selectedTagIds } = this.state

        const errors = []
        if (!name.trim()) {
            errors.push('Company name is required')
        }

        if (errors.length > 0) {
            this.setState({ errors, success: null })
            return
        }

        this.setState({ loading: true })

        try {
            const request = new ApiRequest({
                url: '/review/createCompany',
                method: 'POST',
                params: {
                    name: name.trim(),
                    slug: slug.trim(),
                    description: description.trim(),
                    website: website.trim(),
                    tags: selectedTagIds
                }
            })

            const response = await request.exec()

            if (response.status === 'OK') {
                if (this.props.navigate) {
                    this.props.navigate('/admin/companies')
                }
            } else {
                this.setState({ 
                    errors: [response.response?.error || 'Failed to create company'],
                    success: null,
                    loading: false
                })
            }
        } catch (error) {
            console.error('Failed to create company:', error)
            this.setState({ 
                errors: ['Failed to create company'],
                success: null,
                loading: false
            })
        }
    }

    handleTagToggle = (tagId) => {
        const { selectedTagIds } = this.state
        const newSelectedTagIds = selectedTagIds.includes(tagId)
            ? selectedTagIds.filter(id => id !== tagId)
            : [...selectedTagIds, tagId]
        
        this.setState({ selectedTagIds: newSelectedTagIds })
    }

    handleNameChange = (e) => {
        const name = e.target.value
        this.setState({ name })
        
        if (!this.state.slug) {
            const slug = name.toLowerCase().replace(/[^a-zA-Z0-9]+/g, '-').replace(/^-+|-+$/g, '')
            this.setState({ slug })
        }
    }

    render() {
        const { tags, selectedTagIds, name, slug, description, website, loading, errors } = this.state

        return (
            <div className="container">
                <div className="admin-page">
                    <h1>Create Company</h1>
                    
                    {errors.length > 0 && (
                        <div className="alert alert-error">
                            <ul style={{ margin: 0, paddingLeft: '1.25rem' }}>
                                {errors.map((error, index) => (
                                    <li key={index}>{error}</li>
                                ))}
                            </ul>
                        </div>
                    )}

                    <div className="admin-form-card">
                        <form onSubmit={this.handleSubmit}>
                            <div className="form-group">
                                <label htmlFor="name">Company Name *</label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    className="form-input"
                                    value={name}
                                    onChange={this.handleNameChange}
                                    required
                                />
                            </div>

                            <div className="form-group">
                                <label htmlFor="slug">Slug</label>
                                <input
                                    type="text"
                                    id="slug"
                                    name="slug"
                                    className="form-input"
                                    value={slug}
                                    onChange={(e) => this.setState({ slug: e.target.value })}
                                    placeholder="auto-generated if empty"
                                />
                                <small className="form-hint">URL-friendly identifier (e.g., company-name)</small>
                            </div>

                            <div className="form-group">
                                <label htmlFor="description">Description</label>
                                <textarea
                                    id="description"
                                    name="description"
                                    className="form-textarea"
                                    rows="5"
                                    value={description}
                                    onChange={(e) => this.setState({ description: e.target.value })}
                                />
                            </div>

                            <div className="form-group">
                                <label htmlFor="website">Website URL</label>
                                <input
                                    type="url"
                                    id="website"
                                    name="website"
                                    className="form-input"
                                    value={website}
                                    onChange={(e) => this.setState({ website: e.target.value })}
                                    placeholder="https://example.com"
                                />
                            </div>

                            <div className="form-group">
                                <label>Tags</label>
                                <div className="tags-checkbox-list">
                                    {tags.map((tag) => (
                                        <label key={tag.id} className="tag-checkbox">
                                            <input
                                                type="checkbox"
                                                checked={selectedTagIds.includes(tag.id)}
                                                onChange={() => this.handleTagToggle(tag.id)}
                                            />
                                            <span
                                                className="tag-badge"
                                                style={{
                                                    backgroundColor: `${tag.color}20`,
                                                    color: tag.color,
                                                    borderColor: tag.color
                                                }}
                                            >
                                                {tag.name}
                                            </span>
                                        </label>
                                    ))}
                                </div>
                            </div>

                            <div className="form-actions">
                                <button type="submit" className="btn btn-primary btn-large" disabled={loading}>
                                    {loading ? 'Creating...' : 'Create Company'}
                                </button>
                                <Link to="/admin/companies" className="btn btn-outline btn-large">
                                    Cancel
                                </Link>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        )
    }
}

export default withRouter(Company);

