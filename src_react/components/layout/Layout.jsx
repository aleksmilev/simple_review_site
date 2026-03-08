import { Component } from 'react'
import Header from './header'
import Footer from './footer'
import '../../style/layout.css'

class Layout extends Component {
    render() {
        return (
            <div className="layout">
                <Header />
                <main className="main-content">
                    {this.props.children}
                </main>
                <Footer />
            </div>
        )
    }
}

export default Layout

