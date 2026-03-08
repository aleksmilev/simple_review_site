import { Component } from 'react'
import { Link } from 'react-router-dom'
import { withRouter } from '../../../services/withRouter'
import ApiRequest from '../../../services/api'
import '../../../style/legal.css'
import '../../../style/company.css'

class Search extends Component {
    constructor(props) {
        super(props)
        this.state = {
            companies: [],
            tags: [],
            query: '',
            selectedTag: null,
            loading: true,
            searchQuery: ''
        }
    }

    componentDidMount() {
        this.loadTags()
        this.parseUrlParams()
    }

    componentDidUpdate(prevProps) {
        const currentSearch = this.props.location?.search || ''
        const prevSearch = prevProps.location?.search || ''
        
        if (currentSearch !== prevSearch) {
            this.parseUrlParams()
        }
    }

    parseUrlParams = () => {
        const location = this.props.location || {}
        const searchParams = new URLSearchParams(location.search || '')
        const query = searchParams.get('query') || ''
        const tagId = searchParams.get('tag')
        
        this.setState({
            query: query,
            selectedTag: tagId ? parseInt(tagId) : null,
            searchQuery: query
        }, () => {
            if (query) {
                this.searchByQuery(query)
            } else if (tagId) {
                this.searchByTag(parseInt(tagId))
            } else {
                this.setState({ companies: [], loading: false })
            }
        })
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

    searchByQuery = async (query) => {
        if (!query.trim()) {
            this.setState({ companies: [], loading: false })
            return
        }

        this.setState({ loading: true })
        
        try {
            const request = new ApiRequest({
                url: '/review/searchByCompany',
                method: 'POST',
                params: { query: query.trim() }
            })
            
            const response = await request.exec()
            
            if (response.status === 'OK' && response.response?.companies) {
                this.setState({
                    companies: response.response.companies,
                    loading: false
                })
            } else {
                this.setState({ companies: [], loading: false })
            }
        } catch (error) {
            console.error('Failed to search companies:', error)
            this.setState({ companies: [], loading: false })
        }
    }

    searchByTag = async (tagId) => {
        this.setState({ loading: true })
        
        try {
            const request = new ApiRequest({
                url: '/review/searchByTag',
                method: 'POST',
                params: { tag_id: tagId }
            })
            
            const response = await request.exec()
            
            if (response.status === 'OK' && response.response?.companies) {
                this.setState({
                    companies: response.response.companies,
                    loading: false
                })
            } else {
                this.setState({ companies: [], loading: false })
            }
        } catch (error) {
            console.error('Failed to search by tag:', error)
            this.setState({ companies: [], loading: false })
        }
    }

    handleSearchSubmit = (e) => {
        e.preventDefault()
        const query = e.target.query.value.trim()
        
        if (query && this.props.navigate) {
            this.props.navigate(`/review/search?query=${encodeURIComponent(query)}`)
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
                    <p className="company-description">
                        {company.description.length > 150 
                            ? `${company.description.substring(0, 150)}...` 
                            : company.description}
                    </p>
                )}
                
                <div className="company-card-footer">
                    <div className="company-stats">
                        <span className="stat-item">
                            <strong>{company.total_reviews || 0}</strong> 
                            {' '}{company.total_reviews === 1 ? 'review' : 'reviews'}
                        </span>
                    </div>
                    <Link to={`/review/company/${company.id}`} className="btn btn-primary btn-small">
                        View Reviews
                    </Link>
                </div>
            </div>
        )
    }

    renderIntroText = (query, selectedTag, selectedTagObj) => {
        if (query) {
            return (
                <p className="intro-text">
                    Search results for: <strong>{query}</strong>
                </p>
            )
        }
        
        if (selectedTag && selectedTagObj) {
            return (
                <p className="intro-text">
                    Companies tagged with: <strong>{selectedTagObj.name}</strong>
                </p>
            )
        }
        
        return null
    }

    renderTagsFilter = (tags, selectedTag) => {
        if (tags.length === 0) return null

        return (
            <div className="tags-filter">
                <h3>Filter by Tag</h3>
                <div className="tags-list">
                    <Link
                        to="/review/search"
                        className={`tag-badge ${!selectedTag ? 'tag-active' : ''}`}
                    >
                        All
                    </Link>
                    {tags.map((tag) => (
                        <Link
                            key={tag.id}
                            to={`/review/search?tag=${tag.id}`}
                            className={`tag-badge ${selectedTag === tag.id ? 'tag-active' : ''}`}
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

    renderSearchForm = (searchQuery) => {
        return (
            <div className="search-form-container">
                <form onSubmit={this.handleSearchSubmit} className="search-form">
                    <div className="search-form-group">
                        <div className="search-input-wrapper">
                            <svg className="search-form-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input 
                                type="text" 
                                name="query" 
                                defaultValue={searchQuery}
                                placeholder="Search companies..." 
                                className="search-form-input"
                            />
                        </div>
                        <button type="submit" className="btn btn-primary search-submit-btn">Search</button>
                    </div>
                </form>
            </div>
        )
    }

    renderResults = (companies, selectedTag) => {
        if (companies.length === 0) {
            return (
                <div className="empty-state">
                    <div className="empty-icon">🔍</div>
                    <h2>No Results Found</h2>
                    <p>
                        {selectedTag 
                            ? 'No companies found with this tag.' 
                            : 'No companies found matching your search query. Try a different search term.'}
                    </p>
                    <Link to="/review/company" className="btn btn-primary">Browse All Companies</Link>
                </div>
            )
        }

        return (
            <div className="companies-grid">
                {companies.map((company) => this.renderCompanyCard(company))}
            </div>
        )
    }

    render() {
        const { companies, tags, query, selectedTag, loading, searchQuery } = this.state

        if (loading) {
            return (
                <div className="container">
                    <div className="legal-page">
                        <p>Loading...</p>
                    </div>
                </div>
            )
        }

        const selectedTagObj = tags.find(tag => tag.id === selectedTag)

        return (
            <div className="container">
                <div className="legal-page">
                    <h1>Search Results</h1>
                    
                    {this.renderIntroText(query, selectedTag, selectedTagObj)}
                    {this.renderTagsFilter(tags, selectedTag)}
                    {this.renderSearchForm(searchQuery)}
                    {this.renderResults(companies, selectedTag)}
                    
                    <div style={{ marginTop: '2rem' }}>
                        <Link to="/review/company" className="btn btn-outline">Back to Companies</Link>
                    </div>
                </div>
            </div>
        )
    }
}

export default withRouter(Search);

