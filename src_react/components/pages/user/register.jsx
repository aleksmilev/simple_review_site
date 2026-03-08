import { Component } from 'react'
import { Link } from 'react-router-dom'
import Form from '../../common/Form'
import Popup from '../../common/Popup'
import TokenStorage from '../../../services/token'
import UserService from '../../../services/user'
import { withRouter } from '../../../services/withRouter'
import '../../../style/auth.css'

class Register extends Component {
    constructor(props) {
        super(props)
        this.state = {
            showPopup: false,
            popupType: 'success',
            popupMessage: ''
        }
    }

    handleSubmit = async (e) => {
        const formData = new FormData(e.target)
        const password = formData.get('password')
        const confirmPassword = formData.get('confirm_password')

        if (password !== confirmPassword) {
            e.preventDefault()
            this.setState({
                showPopup: true,
                popupType: 'error',
                popupMessage: 'Passwords do not match. Please try again.'
            })
            return false
        }

        const confirmPasswordInput = e.target.querySelector('input[name="confirm_password"]')
        if (confirmPasswordInput) {
            confirmPasswordInput.removeAttribute('name')
        }
        
        return true
    }

    handleSuccess = (response, data) => {
        console.log('Register response:', response)
        
        if (response.token) {
            TokenStorage.setToken(response.token)
            
            if (response.user) {
                UserService.setUser(response.user)
            } else if (response.username) {
                UserService.setUser({
                    username: response.username,
                    role: response.role || 'user'
                })
            }
            
            const message = response.message || 'Account created successfully! Redirecting...'
            this.setState({
                showPopup: true,
                popupType: 'success',
                popupMessage: message
            })
            setTimeout(() => {
                if (this.props.navigate) {
                    this.props.navigate('/home')
                }
            }, 1500)
        } else {
            const message = response.message || 'Account created successfully! Please login.'
            this.setState({
                showPopup: true,
                popupType: 'success',
                popupMessage: message
            })
            setTimeout(() => {
                if (this.props.navigate) {
                    this.props.navigate('/user/login')
                }
            }, 1500)
        }
    }

    handleError = (error, data) => {
        console.log('Register error:', error)
        const errorMessage = error?.error || error?.message || 'Registration failed. Please try again.'
        this.setState({
            showPopup: true,
            popupType: 'error',
            popupMessage: errorMessage
        })
    }

    handlePopupClose = () => {
        this.setState({ showPopup: false })
    }

    render() {
        const { showPopup, popupType, popupMessage } = this.state

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
                    <div className="auth-page">
                        <div className="auth-card">
                            <h1>Create Account</h1>
                            <p className="auth-subtitle">Join ReviewHub and start sharing your experiences</p>
                            
                            <Form 
                                config={{ controller: 'user', method: 'register' }}
                                onSuccess={this.handleSuccess}
                                onError={this.handleError}
                                onSubmit={this.handleSubmit}
                                formClassName="auth-form"
                            >
                                <Form.Input 
                                    name="username" 
                                    type="text" 
                                    label="Username" 
                                    required 
                                    autoFocus
                                />
                                <small style={{ marginTop: '-1rem', marginBottom: '0.5rem', color: '#6b7280', fontSize: '0.875rem' }}>At least 3 characters</small>
                                
                                <Form.Input 
                                    name="email" 
                                    type="email" 
                                    label="Email" 
                                    required 
                                />
                                
                                <Form.Input 
                                    name="password" 
                                    type="password" 
                                    label="Password" 
                                    required 
                                />
                                <small style={{ marginTop: '-1rem', marginBottom: '0.5rem', color: '#6b7280', fontSize: '0.875rem' }}>At least 6 characters</small>
                                
                                <Form.Input 
                                    name="confirm_password" 
                                    type="password" 
                                    label="Confirm Password" 
                                    required 
                                />
                                
                                <Form.Submit className="btn btn-primary btn-block">
                                    Create Account
                                </Form.Submit>
                            </Form>
                            
                            <div className="auth-footer">
                                <p>Already have an account? <Link to="/user/login">Sign in here</Link></p>
                            </div>
                        </div>
                    </div>
                </div>
            </>
        )
    }
}

export default withRouter(Register)

