import { Component } from 'react'
import { Link, NavLink } from 'react-router-dom'
import { withRouter } from '../../services/withRouter'
import UserService from '../../services/user'
import '../../style/header.css'

class Header extends Component {
    isAdmin = () => {
        return UserService.isAdmin()
    }

    isLoggedIn = () => {
        return UserService.isLoggedIn()
    }

    handleLogout = () => {
        UserService.logout()
        if (this.props.navigate) {
            this.props.navigate('/home')
        }
        window.location.reload()
    }

    handleSearchSubmit = (e) => {
        e.preventDefault()
        const query = e.target.query.value.trim()
        if (query && this.props.navigate) {
            this.props.navigate(`/review/search?query=${encodeURIComponent(query)}`)
        }
    }

    render() {
        const loggedIn = this.isLoggedIn();
        const isAdmin = this.isAdmin();
        const location = this.props.location || { pathname: window.location.pathname }

        return (
            <header className="header">
                <div className="header-container">
                    <div className="header-content">
                        <Link to="/home" className="logo">
                            <div className="logo-icon">R</div>
                            <span>ReviewHub</span>
                        </Link>
                        
                        <nav className="nav">
                            <ul className="nav-links">
                                <li>
                                    <NavLink 
                                        to="/home" 
                                        className={({ isActive }) => `nav-link ${isActive ? 'active' : ''}`}
                                    >
                                        Home
                                    </NavLink>
                                </li>
                                <li>
                                    <NavLink 
                                        to="/review/company" 
                                        className={({ isActive }) => `nav-link ${isActive ? 'active' : ''}`}
                                    >
                                        Companies
                                    </NavLink>
                                </li>
                                <li>
                                    <NavLink 
                                        to="/review" 
                                        className={({ isActive }) => `nav-link ${isActive ? 'active' : ''}`}
                                    >
                                        Reviews
                                    </NavLink>
                                </li>
                            </ul>
                        </nav>
                        
                        <form onSubmit={this.handleSearchSubmit} className="search-container">
                            <button type="submit" className="search-icon-button" aria-label="Search companies">
                                <svg className="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                            <input type="search" name="query" className="search-input" placeholder="Search companies..." aria-label="Search companies" />
                        </form>
                        
                        <div className="user-actions">
                            {!loggedIn ? (
                                <>
                                    <Link to="/user/login" className="btn btn-outline">
                                        <span className="btn-text">Login</span>
                                    </Link>
                                    <Link to="/user/register" className="btn btn-primary">
                                        <span className="btn-text">Sign Up</span>
                                    </Link>
                                </>
                            ) : (
                                <>
                                    <div className="user-menu">
                                        <Link to="/user/profile" className="user-avatar" title={UserService.getUser()?.username || 'User'}>
                                            {UserService.getUser()?.username ? UserService.getUser().username.charAt(0).toUpperCase() : 'U'}
                                        </Link>
                                    </div>
                                    {isAdmin && (
                                        <Link to="/admin" className="btn btn-outline">
                                            <span className="btn-text">Admin</span>
                                        </Link>
                                    )}
                                    <button onClick={this.handleLogout} className="btn btn-outline">
                                        <span className="btn-text">Logout</span>
                                    </button>
                                </>
                            )}
                            <button className="mobile-menu-toggle" aria-label="Toggle menu">
                                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </header>
        )
    }
}

export default withRouter(Header)
