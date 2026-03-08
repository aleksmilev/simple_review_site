import { Component } from 'react'
import Form from '../../common/Form'
import Popup from '../../common/Popup'
import '../../../style/legal.css'
import '../../../style/contact.css'

class Contact extends Component {
    constructor(props) {
        super(props)
        this.state = {
            showPopup: false,
            popupType: 'success',
            popupMessage: ''
        }
    }

    handleSuccess = (response, data) => {
        console.log('Form submitted successfully!', response)
        this.setState({
            showPopup: true,
            popupType: 'success',
            popupMessage: 'Your message has been sent successfully! We will get back to you soon.'
        })
    }

    handleError = (error, data) => {
        console.error('Form submission error:', error)
        const errorMessage = error?.error || 'An error occurred while sending your message. Please try again.'
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
                    <div className="legal-page">
                    <h1>Contact Us</h1>
                    <p className="intro-text">We'd love to hear from you! Whether you have a question, feedback, or need support, feel free to reach out to us.</p>
                    
                    <div className="contact-grid">
                        <div className="contact-section">
                            <h2>Get in Touch</h2>
                            <Form 
                                config={{ controller: 'legal', method: 'form' }}
                                onSuccess={this.handleSuccess}
                                onError={this.handleError}
                            >
                                <Form.Input 
                                    name="name" 
                                    type="text" 
                                    label="Name" 
                                    required 
                                />
                                
                                <Form.Input 
                                    name="email" 
                                    type="email" 
                                    label="Email" 
                                    required 
                                />
                                
                                <Form.Select 
                                    name="subject" 
                                    label="Subject" 
                                    required
                                    placeholder="Select a subject"
                                    options={[
                                        { value: 'general', label: 'General Inquiry' },
                                        { value: 'support', label: 'Support Request' },
                                        { value: 'feedback', label: 'Feedback' },
                                        { value: 'report', label: 'Report an Issue' },
                                        { value: 'other', label: 'Other' }
                                    ]}
                                />
                                
                                <Form.Textarea 
                                    name="message" 
                                    label="Message" 
                                    required
                                    rows={6}
                                />
                                
                                <Form.Submit className="btn btn-primary">
                                    Send Message
                                </Form.Submit>
                            </Form>
                        </div>
                        
                        <div className="contact-info">
                            <h2>Other Ways to Reach Us</h2>
                            <div className="info-item">
                                <h3>Email</h3>
                                <p>support@reviewhub.com</p>
                            </div>
                            
                            <div className="info-item">
                                <h3>Response Time</h3>
                                <p>We typically respond within 24-48 hours during business days.</p>
                            </div>
                            
                            <div className="info-item">
                                <h3>Business Hours</h3>
                                <p>Monday - Friday: 9:00 AM - 5:00 PM (EST)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </>
        )
    }
}

export default Contact

