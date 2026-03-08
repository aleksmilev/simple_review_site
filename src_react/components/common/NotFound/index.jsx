import { Component } from 'react'
import '../../../style/NotFound.css'

class NotFound extends Component {
    render() {
        return (
            <div className="not-found-wrapper">
                <div className="not-found-content">
                    <div className="not-found-code">404</div>
                    <div className="not-found-title">Page not found</div>
                    <p className="not-found-message">
                        The page you are looking for doesn&apos;t exist or may have been moved.
                    </p>
                </div>
            </div>
        )
    }
}

export default NotFound

