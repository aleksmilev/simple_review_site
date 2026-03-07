import { Component } from 'react'
import { withRouter } from './withRouter'

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

    async loadComponent() {
        const path = this.getPath()
        const parsed = this.parsePath(path)
        
        if (!parsed) {
            this.setState({
                Component: null,
                loading: false,
                error: 'Invalid path format. Expected: /directory/view'
            })
            return
        }

        const { directory, view } = parsed

        try {
            this.setState({ loading: true, error: null })
            
            const module = await import(`../components/main/${directory}/${view}.jsx`)
            const Component = module.default

            this.setState({
                Component,
                loading: false,
                error: null
            })
        } catch (error) {
            console.error(`Failed to load component: ${directory}/${view}`, error)
            
            try {
                const NotFoundComponent = (await import('../components/global/not_found/index.jsx')).default
                this.setState({
                    Component: NotFoundComponent,
                    loading: false,
                    error: null
                })
            } catch (notFoundError) {
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
