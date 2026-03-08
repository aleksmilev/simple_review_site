import { Component } from 'react'
import { Link } from 'react-router-dom'
import { withRouter } from '../../../services/withRouter'
import ApiRequest from '../../../services/api'
import NotFound from '../../common/NotFound'
import '../../../style/admin.css'

class Tag extends Component {
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
            tag: null,
            name: '',
            color: '#3b82f6',
            description: '',
            loading: true,
            errors: [],
            success: null
        }
    }

    componentDidMount() {
        const { id } = this.props
        if (id) {
            this.loadTag(id)
        }
    }

    loadTag = async (id) => {
        this.setState({ loading: true })

        try {
            const request = new ApiRequest({
                url: '/review/getTags',
                method: 'GET',
                params: {}
            })

            const response = await request.exec()

            if (response.status === 'OK' && response.response?.tags) {
                const tag = response.response.tags.find(t => t.id === parseInt(id))
                
                if (tag) {
                    this.setState({
                        tag: tag,
                        name: tag.name || '',
                        color: tag.color || '#3b82f6',
                        description: tag.description || '',
                        loading: false
                    })
                } else {
                    this.setState({ loading: false })
                    if (this.props.navigate) {
                        this.props.navigate('/admin/tags')
                    }
                }
            } else {
                this.setState({ loading: false })
                if (this.props.navigate) {
                    this.props.navigate('/admin/tags')
                }
            }
        } catch (error) {
            console.error('Failed to load tag:', error)
            this.setState({ loading: false })
            if (this.props.navigate) {
                this.props.navigate('/admin/tags')
            }
        }
    }

    handleSubmit = async (e) => {
        e.preventDefault()
        const { id } = this.props
        const { name, color, description } = this.state

        const errors = []
        if (!name.trim()) {
            errors.push('Tag name is required')
        }

        if (errors.length > 0) {
            this.setState({ errors, success: null })
            return
        }

        try {
            const request = new ApiRequest({
                url: '/review/updateTag',
                method: 'POST',
                params: {
                    id: parseInt(id),
                    name: name.trim(),
                    color: color.trim(),
                    description: description.trim()
                }
            })

            const response = await request.exec()

            if (response.status === 'OK') {
                if (this.props.navigate) {
                    this.props.navigate('/admin/tags')
                }
            } else {
                this.setState({ 
                    errors: [response.response?.error || 'Failed to update tag'],
                    success: null
                })
            }
        } catch (error) {
            console.error('Failed to update tag:', error)
            this.setState({ 
                errors: ['Failed to update tag'],
                success: null
            })
        }
    }

    handleColorChange = (e) => {
        const color = e.target.value
        this.setState({ color })
    }

    handleColorTextChange = (e) => {
        const color = e.target.value
        this.setState({ color })
    }

    render() {
        const { id } = this.props
        const { tag, name, color, description, loading, errors } = this.state

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

        if (!tag) {
            return <NotFound />
        }

        return (
            <div className="container">
                <div className="admin-page">
                    <h1>Edit Tag</h1>
                    
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
                                <label htmlFor="name">Tag Name *</label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    className="form-input"
                                    value={name}
                                    onChange={(e) => this.setState({ name: e.target.value })}
                                    required
                                />
                            </div>

                            <div className="form-group">
                                <label htmlFor="color">Color *</label>
                                <div style={{ display: 'flex', gap: '1rem', alignItems: 'center' }}>
                                    <input
                                        type="color"
                                        id="color"
                                        name="color"
                                        value={color}
                                        onChange={this.handleColorChange}
                                        style={{
                                            width: '80px',
                                            height: '40px',
                                            borderRadius: '0.5rem',
                                            border: '2px solid #d1d5db',
                                            cursor: 'pointer'
                                        }}
                                    />
                                    <input
                                        type="text"
                                        className="form-input"
                                        value={color}
                                        onChange={this.handleColorTextChange}
                                        placeholder="#3b82f6"
                                        style={{ flex: 1 }}
                                    />
                                </div>
                                <small className="form-hint">Choose a color for this tag</small>
                            </div>

                            <div className="form-group">
                                <label htmlFor="description">Description</label>
                                <textarea
                                    id="description"
                                    name="description"
                                    className="form-textarea"
                                    rows="4"
                                    value={description}
                                    onChange={(e) => this.setState({ description: e.target.value })}
                                />
                            </div>

                            <div className="form-actions">
                                <button type="submit" className="btn btn-primary btn-large">
                                    Update Tag
                                </button>
                                <Link to="/admin/tags" className="btn btn-outline btn-large">
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
            name: '',
            color: '#3b82f6',
            description: '',
            loading: false,
            errors: [],
            success: null
        }
    }

    handleSubmit = async (e) => {
        e.preventDefault()
        const { name, color, description } = this.state

        const errors = []
        if (!name.trim()) {
            errors.push('Tag name is required')
        }

        if (errors.length > 0) {
            this.setState({ errors, success: null })
            return
        }

        this.setState({ loading: true })

        try {
            const request = new ApiRequest({
                url: '/review/createTag',
                method: 'POST',
                params: {
                    name: name.trim(),
                    color: color.trim(),
                    description: description.trim()
                }
            })

            const response = await request.exec()

            if (response.status === 'OK') {
                if (this.props.navigate) {
                    this.props.navigate('/admin/tags')
                }
            } else {
                this.setState({ 
                    errors: [response.response?.error || 'Failed to create tag'],
                    success: null,
                    loading: false
                })
            }
        } catch (error) {
            console.error('Failed to create tag:', error)
            this.setState({ 
                errors: ['Failed to create tag'],
                success: null,
                loading: false
            })
        }
    }

    handleColorChange = (e) => {
        const color = e.target.value
        this.setState({ color })
    }

    handleColorTextChange = (e) => {
        const color = e.target.value
        this.setState({ color })
    }

    render() {
        const { name, color, description, loading, errors } = this.state

        return (
            <div className="container">
                <div className="admin-page">
                    <h1>Create Tag</h1>
                    
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
                                <label htmlFor="name">Tag Name *</label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    className="form-input"
                                    value={name}
                                    onChange={(e) => this.setState({ name: e.target.value })}
                                    required
                                />
                            </div>

                            <div className="form-group">
                                <label htmlFor="color">Color *</label>
                                <div style={{ display: 'flex', gap: '1rem', alignItems: 'center' }}>
                                    <input
                                        type="color"
                                        id="color"
                                        name="color"
                                        value={color}
                                        onChange={this.handleColorChange}
                                        style={{
                                            width: '80px',
                                            height: '40px',
                                            borderRadius: '0.5rem',
                                            border: '2px solid #d1d5db',
                                            cursor: 'pointer'
                                        }}
                                    />
                                    <input
                                        type="text"
                                        className="form-input"
                                        value={color}
                                        onChange={this.handleColorTextChange}
                                        placeholder="#3b82f6"
                                        style={{ flex: 1 }}
                                    />
                                </div>
                                <small className="form-hint">Choose a color for this tag</small>
                            </div>

                            <div className="form-group">
                                <label htmlFor="description">Description</label>
                                <textarea
                                    id="description"
                                    name="description"
                                    className="form-textarea"
                                    rows="4"
                                    value={description}
                                    onChange={(e) => this.setState({ description: e.target.value })}
                                />
                            </div>

                            <div className="form-actions">
                                <button type="submit" className="btn btn-primary btn-large" disabled={loading}>
                                    {loading ? 'Creating...' : 'Create Tag'}
                                </button>
                                <Link to="/admin/tags" className="btn btn-outline btn-large">
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

export default withRouter(Tag);

