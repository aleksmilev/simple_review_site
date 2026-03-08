import { Component } from 'react'
import { Link } from 'react-router-dom'
import Form from '../../common/Form'
import Popup from '../../common/Popup'
import TokenStorage from '../../../services/token'
import UserService from '../../../services/user'
import ApiRequest from '../../../services/api'
import { withRouter } from '../../../services/withRouter'
import '../../../style/auth.css'

class Login extends Component {
    constructor(props) {
        super(props)
        this.state = {
            showPopup: false,
            popupType: 'success',
            popupMessage: ''
        }
    }

    handleSuccess = async (response, data) => {
        if (response.token) {
            TokenStorage.setToken(response.token)
            
            try {
                const userRequest = new ApiRequest({
                    url: '/user/user',
                    method: 'GET',
                    params: {}
                })
                
                const userResponse = await userRequest.exec()
                
                if (userResponse.status === 'OK' && userResponse.response) {
                    UserService.setUser(userResponse.response)
                } else {
                    if (response.user) {
                        UserService.setUser(response.user)
                    } else if (response.username) {
                        UserService.setUser({
                            username: response.username,
                            role: response.role || 'user'
                        })
                    }
                }
            } catch (error) {
                console.error('Failed to fetch user data:', error)
                if (response.user) {
                    UserService.setUser(response.user)
                } else if (response.username) {
                    UserService.setUser({
                        username: response.username,
                        role: response.role || 'user'
                    })
                }
            }
            
            const message = response.message || 'Login successful! Redirecting...'
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
            const message = response.message || 'Login successful!'
            this.setState({
                showPopup: true,
                popupType: 'success',
                popupMessage: message
            })
        }
    }

    handleError = (error, data) => {
        const errorMessage = error?.error || error?.message || 'Invalid username or password. Please try again.'
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
                            <h1>Login</h1>
                            <p className="auth-subtitle">Sign in to your account to continue</p>
                            
                            <Form 
                                config={{ controller: 'user', method: 'login' }}
                                onSuccess={this.handleSuccess}
                                onError={this.handleError}
                                formClassName="auth-form"
                            >
                                <Form.Input 
                                    name="username" 
                                    type="text" 
                                    label="Username" 
                                    required 
                                    autoFocus
                                />
                                
                                <Form.Input 
                                    name="password" 
                                    type="password" 
                                    label="Password" 
                                    required 
                                />
                                
                                <Form.Submit className="btn btn-primary btn-block">
                                    Sign In
                                </Form.Submit>
                            </Form>
                            
                            <div className="auth-footer">
                                <p>Don't have an account? <Link to="/user/register">Sign up here</Link></p>
                            </div>
                        </div>
                    </div>
                </div>
            </>
        )
    }
}

export default withRouter(Login)

