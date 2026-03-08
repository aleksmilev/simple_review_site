import { Component } from 'react'
import { withRouter } from '../services/withRouter'
import UserService from '../services/user'

class ActiveComponent extends Component {
    constructor(props) {
        super(props)
        this.state = {
            Component: null,
            loading: true,
            error: null
        }
    }

    async componentDidMount() {
        await this.loadComponent()
    }

    async componentDidUpdate(prevProps) {
        if (this.getPath() !== this.getPathFromProps(prevProps)) {
            await this.loadComponent()
        }
    }

    getPath() {
        if (this.props.path) {
            return this.props.path
        }
        if (this.props.location) {
            return this.props.location.pathname
        }
        return window.location.pathname
    }

    getPathFromProps(props) {
        if (props && props.path) {
            return props.path
        }
        if (props && props.location) {
            return props.location.pathname
        }
        return window.location.pathname
    }

    parsePath(path) {
        const cleanPath = path.startsWith('/') ? path.slice(1) : path
        const parts = cleanPath.split('/').filter(Boolean)
        
        if (parts.length === 0 || (parts.length === 1 && parts[0] === '')) {
            return {
                directory: 'home',
                view: 'index'
            }
        }
        
        if (parts.length === 1) {
            return {
                directory: parts[0],
                view: 'index'
            }
        }
        
        if (parts.length >= 2) {
            return {
                directory: parts[0],
                view: parts[1]
            }
        }
        
        return null
    }

    getPageTitle(path, parsed) {
        if (!parsed) {
            return 'ReviewHub'
        }

        const { directory, view } = parsed
        const pathLower = path.toLowerCase()
        const pathParts = path.split('/').filter(Boolean)

        if (pathLower.startsWith('/admin/company/edit')) {
            return 'Edit Company - ReviewHub'
        }

        if (pathLower.startsWith('/admin/company/create')) {
            return 'Create Company - ReviewHub'
        }

        if (pathLower.startsWith('/admin/tag/edit')) {
            return 'Edit Tag - ReviewHub'
        }

        if (pathLower.startsWith('/admin/tag/create')) {
            return 'Create Tag - ReviewHub'
        }

        const titleMap = {
            '/home': 'Home',
            '/review/company': 'Companies',
            '/review': 'Reviews',
            '/review/search': 'Search',
            '/user/login': 'Login',
            '/user/register': 'Register',
            '/user/profile': 'Profile',
            '/user/reviews': 'My Reviews',
            '/admin': 'Admin Dashboard',
            '/admin/reviews': 'Manage Reviews',
            '/admin/companies': 'Manage Companies',
            '/admin/tags': 'Manage Tags',
            '/admin/users': 'Manage Users',
            '/legal/terms': 'Terms of Service',
            '/legal/privacy': 'Privacy Policy'
        }

        if (titleMap[pathLower]) {
            return `${titleMap[pathLower]} - ReviewHub`
        }

        if (directory === 'admin' && view === 'index') {
            return 'Admin Dashboard - ReviewHub'
        }

        if (directory === 'admin') {
            const viewName = view.charAt(0).toUpperCase() + view.slice(1)
            return `${viewName} - Admin - ReviewHub`
        }

        if (directory === 'user') {
            const viewName = view.charAt(0).toUpperCase() + view.slice(1)
            return `${viewName} - ReviewHub`
        }

        if (directory === 'review') {
            const viewName = view.charAt(0).toUpperCase() + view.slice(1)
            return `${viewName} - ReviewHub`
        }

        if (directory === 'legal') {
            const viewName = view.charAt(0).toUpperCase() + view.slice(1)
            return `${viewName} - ReviewHub`
        }

        return 'ReviewHub'
    }

    updateDocumentTitle(path, parsed) {
        const title = this.getPageTitle(path, parsed)
        document.title = title
    }

    async loadComponent() {
        const path = this.getPath()
        const parsed = this.parsePath(path)
        
        if (!parsed) {
            this.updateDocumentTitle(path, null)
            this.setState({
                Component: null,
                loading: false,
                error: 'Invalid path format. Expected: /directory/view'
            })
            return
        }

        const { directory, view } = parsed

        if (directory === 'admin') {
            if (!UserService.isAdmin()) {
                if (this.props.navigate) {
                    this.props.navigate('/user/login')
                }
                this.setState({
                    Component: null,
                    loading: false,
                    error: null
                })
                return
            }
        }

        try {
            this.setState({ loading: true, error: null })
            
            const module = await import(`../components/pages/${directory}/${view}.jsx`)
            const Component = module.default

            this.updateDocumentTitle(path, parsed)

            this.setState({
                Component,
                loading: false,
                error: null
            })
        } catch (error) {
            console.error(`Failed to load component: ${directory}/${view}`, error)
            
            try {
                const NotFoundComponent = (await import('../components/common/NotFound/index.jsx')).default
                this.updateDocumentTitle(path, { directory: 'notfound', view: 'index' })
                this.setState({
                    Component: NotFoundComponent,
                    loading: false,
                    error: null
                })
            } catch (notFoundError) {
                this.updateDocumentTitle(path, null)
                this.setState({
                    Component: null,
                    loading: false,
                    error: `Component not found: ${directory}/${view}`
                })
            }
        }
    }

    render() {
        const { Component: LoadedComponent, loading, error } = this.state

        if (loading) {
            return (
                <div className="container">
                    <div className="loading-spinner"></div>
                </div>
            )
        }

        if (error) {
            return (
                <div className="container">
                    <h1>Error</h1>
                    <p>{error}</p>
                </div>
            )
        }

        if (!LoadedComponent) {
            return (
                <div className="container">
                    <h1>Not Found</h1>
                    <p>Component not found for this path.</p>
                </div>
            )
        }

        return <LoadedComponent />
    }
}

export default withRouter(ActiveComponent)

