import { Component } from 'react'
import { useLocation, useNavigate, useParams } from 'react-router-dom'

export function withRouter(WrappedComponent) {
    function ComponentWithRouterProp(props) {
        const location = useLocation()
        const navigate = useNavigate()
        const params = useParams()
        
        return (
            <WrappedComponent
                {...props}
                location={location}
                navigate={navigate}
                params={params}
            />
        )
    }

    ComponentWithRouterProp.displayName = `withRouter(${WrappedComponent.displayName || WrappedComponent.name || 'Component'})`

    return ComponentWithRouterProp
}

export default withRouter

