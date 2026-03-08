import { Component } from 'react'
import '../../../style/Popup.css'

class Popup extends Component {
    constructor(props) {
        super(props)
        this.state = {
            visible: false,
            fading: false
        }
        this.timeout = null
        this.fadeTimeout = null
    }

    componentDidMount() {
        this.show()
    }

    componentWillUnmount() {
        if (this.timeout) {
            clearTimeout(this.timeout)
        }
        if (this.fadeTimeout) {
            clearTimeout(this.fadeTimeout)
        }
    }

    show = () => {
        this.setState({ visible: true, fading: false })
        
        this.timeout = setTimeout(() => {
            this.fadeOut()
        }, 10000)
    }

    fadeOut = () => {
        this.setState({ fading: true })
        
        this.fadeTimeout = setTimeout(() => {
            this.setState({ visible: false })
            if (this.props.onClose) {
                this.props.onClose()
            }
        }, 300)
    }

    handleClose = () => {
        if (this.timeout) {
            clearTimeout(this.timeout)
        }
        this.fadeOut()
    }

    render() {
        const { type = 'success', message, children } = this.props
        const { visible, fading } = this.state

        if (!visible) {
            return null
        }

        return (
            <div className={`popup-overlay ${fading ? 'fading' : ''}`} onClick={this.handleClose}>
                <div 
                    className={`popup popup-${type} ${fading ? 'fading' : ''}`}
                    onClick={(e) => e.stopPropagation()}
                >
                    <button className="popup-close" onClick={this.handleClose} aria-label="Close">
                        ×
                    </button>
                    <div className="popup-content">
                        {children || message}
                    </div>
                </div>
            </div>
        )
    }
}

export default Popup

