import { Component } from 'react'
import { useLocation, useNavigate, useParams } from 'react-router-dom'

/**
 * Higher Order Component to provide React Router v6 hooks to class components
 * This allows class components to access location, navigate, and params
 */
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

