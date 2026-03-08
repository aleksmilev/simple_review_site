import { Component } from 'react'
import { Link } from 'react-router-dom'
import Form from '../../common/Form'
import Popup from '../../common/Popup'
import UserService from '../../../services/user'
import ApiRequest from '../../../services/api'
import { withRouter } from '../../../services/withRouter'
import '../../../style/profile.css'

class Profile extends Component {
    constructor(props) {
        super(props)
        this.state = {
            user: null,
            loading: true,
            showPopup: false,
            popupType: 'success',
            popupMessage: '',
            reviewCount: 0
        }
    }

    componentDidMount() {
        if (!UserService.isLoggedIn()) {
            if (this.props.navigate) {
                this.props.navigate('/user/login')
            }
            return
        }
        this.loadUserData()
    }

    loadUserData = async () => {
        this.setState({ loading: true })
        
        const user = UserService.getUser()
        if (user) {
            this.setState({ 
                user, 
                reviewCount: user.reviewCount || user.review_count || 0,
                loading: false 
            })
        }
        
        const updatedUser = await UserService.fetchAndUpdateUser()
        if (updatedUser) {
            const decryptedUser = UserService.getUser()
            this.setState({ 
                user: decryptedUser, 
                reviewCount: decryptedUser.reviewCount || decryptedUser.review_count || 0,
                loading: false 
            })
        } else {
            this.setState({ loading: false })
        }
    }

    handleEmailUpdate = async (responseData, formData) => {
        if (responseData?.message) {
            if (responseData.message.includes('successfully')) {
                await UserService.fetchAndUpdateUser()
                const updatedUser = UserService.getUser()
                this.setState({
                    user: updatedUser,
                    reviewCount: updatedUser.reviewCount || updatedUser.review_count || 0,
                    showPopup: true,
                    popupType: 'success',
                    popupMessage: responseData.message
                })
            } else {
                this.setState({
                    showPopup: true,
                    popupType: 'error',
                    popupMessage: responseData.message
                })
            }
        }
    }

    handlePasswordSubmit = async (e) => {
        e.preventDefault()
        
        const formData = new FormData(e.target)
        const newPassword = formData.get('new_password')
        const confirmPassword = formData.get('confirm_password')
        
        if (newPassword && newPassword !== confirmPassword) {
            this.setState({
                showPopup: true,
                popupType: 'error',
                popupMessage: 'New passwords do not match'
            })
            return false
        }
        
        const confirmPasswordInput = e.target.querySelector('[name="confirm_password"]')
        if (confirmPasswordInput) {
            confirmPasswordInput.removeAttribute('name')
        }
        
        return true
    }

    handlePasswordUpdate = async (responseData, formData) => {
        if (responseData?.message) {
            if (responseData.message.includes('successfully')) {
                this.setState({
                    showPopup: true,
                    popupType: 'success',
                    popupMessage: responseData.message
                })
            } else {
                this.setState({
                    showPopup: true,
                    popupType: 'error',
                    popupMessage: responseData.message
                })
            }
        }
    }

    handleError = (error, data) => {
        let errorMessage = 'An error occurred'
        
        if (error?.errors && Array.isArray(error.errors)) {
            errorMessage = error.errors.join(', ')
        } else if (error?.error) {
            errorMessage = error.error
        } else if (error?.message) {
            errorMessage = error.message
        }
        
        this.setState({
            showPopup: true,
            popupType: 'error',
            popupMessage: errorMessage
        })
    }

    handlePopupClose = () => {
        this.setState({ showPopup: false })
    }

    handleLogout = () => {
        UserService.logout()
        if (this.props.navigate) {
            this.props.navigate('/home')
        }
        window.location.reload()
    }

    formatDate = (dateString) => {
        if (!dateString) return 'N/A'
        const date = new Date(dateString)
        return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })
    }

    formatMonthYear = (dateString) => {
        if (!dateString) return 'N/A'
        const date = new Date(dateString)
        return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short' })
    }

    render() {
        const { user, loading, showPopup, popupType, popupMessage, reviewCount } = this.state

        if (loading) {
            return (
                <div className="container">
                    <div className="profile-page">
                        <p>Loading...</p>
                    </div>
                </div>
            )
        }

        if (!user) {
            if (this.props.navigate) {
                this.props.navigate('/user/login')
            }
            return null
        }

        return (
            <>
                {showPopup && (
                    <Popup 
                        type={popupType} 
                        message={popupMessage}
                        onClose={this.handlePopupClose}
                    />
                )}
                <div className="container">
                    <div className="profile-page">
                        <div className="profile-header">
                            <div className="profile-avatar-large">
                                {user.username ? user.username.charAt(0).toUpperCase() : 'U'}
                            </div>
                            <div className="profile-header-info">
                                <h1>{user.username || 'User'}</h1>
                                <p className="profile-email">{user.email || ''}</p>
                                <div className="profile-badge">
                                    <span className={`badge badge-${user.role === 'admin' ? 'admin' : 'user'}`}>
                                        {user.role ? user.role.charAt(0).toUpperCase() + user.role.slice(1) : 'User'}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div className="profile-stats">
                            <div className="stat-card">
                                <div className="stat-icon">📝</div>
                                <div className="stat-content">
                                    <div className="stat-value">{reviewCount}</div>
                                    <div className="stat-label">Reviews</div>
                                </div>
                            </div>
                            <div className="stat-card">
                                <div className="stat-icon">📅</div>
                                <div className="stat-content">
                                    <div className="stat-value">{this.formatMonthYear(user.created_at)}</div>
                                    <div className="stat-label">Member Since</div>
                                </div>
                            </div>
                        </div>
                        
                        <div className="profile-content-grid">
                            <div className="profile-card">
                                <h2 className="profile-card-title">Account Information</h2>
                                <Form 
                                    config={{ controller: 'user', method: 'changeEmail' }}
                                    onSuccess={this.handleEmailUpdate}
                                    onError={this.handleError}
                                    formClassName="profile-form"
                                >
                                    <div className="form-group">
                                        <label htmlFor="username">Username</label>
                                        <input 
                                            type="text" 
                                            id="username" 
                                            className="form-input" 
                                            value={user.username || ''} 
                                            disabled
                                        />
                                        <small className="form-hint">Username cannot be changed</small>
                                    </div>
                                    
                                    <div className="form-group">
                                        <label htmlFor="email">Email Address</label>
                                        <input 
                                            type="email" 
                                            id="email" 
                                            name="email" 
                                            className="form-input" 
                                            defaultValue={user.email || ''}
                                            required
                                        />
                                    </div>
                                    
                                    <div className="form-group">
                                        <label htmlFor="role">Account Type</label>
                                        <input 
                                            type="text" 
                                            id="role" 
                                            className="form-input" 
                                            value={user.role ? user.role.charAt(0).toUpperCase() + user.role.slice(1) : 'User'} 
                                            disabled
                                        />
                                    </div>
                                    
                                    <div className="form-group">
                                        <label>Registration Date</label>
                                        <input 
                                            type="text" 
                                            className="form-input" 
                                            value={this.formatDate(user.created_at)} 
                                            disabled
                                        />
                                    </div>
                                    
                                    <Form.Submit className="btn btn-primary btn-large">
                                        Save Changes
                                    </Form.Submit>
                                </Form>
                            </div>
                            
                            <div className="profile-card">
                                <h2 className="profile-card-title">Change Password</h2>
                                <p className="profile-card-description">Leave blank if you don't want to change your password</p>
                                
                                <Form 
                                    config={{ controller: 'user', method: 'changePassword' }}
                                    onSuccess={this.handlePasswordUpdate}
                                    onError={this.handleError}
                                    onSubmit={this.handlePasswordSubmit}
                                    formClassName="profile-form"
                                >
                                    <div className="form-group">
                                        <label htmlFor="old_password">Current Password</label>
                                        <input 
                                            type="password" 
                                            id="old_password" 
                                            name="old_password" 
                                            className="form-input" 
                                            placeholder="Enter current password"
                                            required
                                        />
                                    </div>
                                    
                                    <div className="form-group">
                                        <label htmlFor="new_password">New Password</label>
                                        <input 
                                            type="password" 
                                            id="new_password" 
                                            name="new_password" 
                                            className="form-input" 
                                            placeholder="Enter new password"
                                            required
                                        />
                                        <small className="form-hint">At least 6 characters</small>
                                    </div>
                                    
                                    <div className="form-group">
                                        <label htmlFor="confirm_password">Confirm New Password</label>
                                        <input 
                                            type="password" 
                                            id="confirm_password" 
                                            name="confirm_password" 
                                            className="form-input" 
                                            placeholder="Confirm new password"
                                            required
                                        />
                                    </div>
                                    
                                    <Form.Submit className="btn btn-primary btn-large">
                                        Update Password
                                    </Form.Submit>
                                </Form>
                            </div>
                        </div>
                        
                        <div className="profile-actions">
                            <Link to="/user/reviews" className="btn btn-outline btn-large">
                                <span>📝</span>
                                <span>View My Reviews</span>
                            </Link>
                            <button onClick={this.handleLogout} className="btn btn-outline btn-large">
                                <span>🚪</span>
                                <span>Logout</span>
                            </button>
                        </div>
                    </div>
                </div>
            </>
        )
    }
}

export default withRouter(Profile)

