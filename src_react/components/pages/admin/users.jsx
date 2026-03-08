import { Component } from 'react'
import { Link } from 'react-router-dom'
import { withRouter } from '../../../services/withRouter'
import ApiRequest from '../../../services/api'
import UserService from '../../../services/user'
import '../../../style/admin.css'

class Users extends Component {
    constructor(props) {
        super(props)
        this.state = {
            users: [],
            loading: true,
            success: null,
            error: null
        }
    }

    componentDidMount() {
        this.loadUsers()
    }

    loadUsers = async () => {
        this.setState({ loading: true, success: null, error: null })

        try {
            const request = new ApiRequest({
                url: '/user/getAllUsers',
                method: 'GET',
                params: {}
            })

            const response = await request.exec()

            if (response.status === 'OK' && response.response?.users) {
                const users = response.response.users.sort((a, b) => {
                    const dateA = new Date(a.created_at || 0)
                    const dateB = new Date(b.created_at || 0)
                    return dateB - dateA
                })

                this.setState({
                    users: users,
                    loading: false
                })
            } else {
                this.setState({ loading: false })
            }
        } catch (error) {
            console.error('Failed to load users:', error)
            this.setState({ loading: false })
        }
    }

    handleDelete = async (userId) => {
        const currentUser = UserService.getUser()
        if (currentUser && currentUser.id === userId) {
            this.setState({ error: 'You cannot delete your own account', success: null })
            return
        }

        if (!window.confirm('Are you sure you want to delete this user?')) {
            return
        }

        try {
            const request = new ApiRequest({
                url: '/user/deleteUser',
                method: 'POST',
                params: { id: userId }
            })

            const response = await request.exec()

            if (response.status === 'OK') {
                this.setState({ success: 'User deleted successfully', error: null })
                this.loadUsers()
            } else {
                this.setState({ error: response.response?.error || 'Failed to delete user', success: null })
            }
        } catch (error) {
            console.error('Failed to delete user:', error)
            this.setState({ error: 'Failed to delete user', success: null })
        }
    }

    handleRoleChange = async (userId, newRole) => {
        const currentUser = UserService.getUser()
        if (currentUser && currentUser.id === userId) {
            this.setState({ error: 'You cannot change your own role', success: null })
            return
        }

        try {
            const request = new ApiRequest({
                url: '/user/changeUserRole',
                method: 'POST',
                params: { id: userId, role: newRole }
            })

            const response = await request.exec()

            if (response.status === 'OK') {
                this.setState({ success: 'User role updated successfully', error: null })
                this.loadUsers()
            } else {
                this.setState({ error: response.response?.error || 'Failed to update user role', success: null })
            }
        } catch (error) {
            console.error('Failed to update user role:', error)
            this.setState({ error: 'Failed to update user role', success: null })
        }
    }

    formatDate = (dateString) => {
        if (!dateString) return 'N/A'
        const date = new Date(dateString)
        return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
    }

    renderAlerts = (success, error) => {
        return (
            <>
                {success && (
                    <div className="alert alert-success">
                        {success}
                    </div>
                )}

                {error && (
                    <div className="alert alert-error">
                        {error}
                    </div>
                )}
            </>
        )
    }

    renderUserRow = (user) => {
        const currentUser = UserService.getUser()
        const isCurrentUser = currentUser && currentUser.id === user.id

        return (
            <tr key={user.id}>
                <td><strong>{user.username}</strong></td>
                <td>{user.email}</td>
                <td>
                    <select
                        className="form-select-small"
                        value={user.role}
                        onChange={(e) => this.handleRoleChange(user.id, e.target.value)}
                        disabled={isCurrentUser}
                    >
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </td>
                <td>{user.review_count || 0}</td>
                <td>{this.formatDate(user.created_at)}</td>
                <td>
                    <div className="admin-actions">
                        {!isCurrentUser && (
                            <button
                                onClick={() => this.handleDelete(user.id)}
                                className="btn btn-small btn-danger"
                            >
                                Delete
                            </button>
                        )}
                    </div>
                </td>
            </tr>
        )
    }

    renderUsersTable = (users) => {
        return (
            <div className="admin-table-container">
                <table className="admin-table">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Reviews</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {users.map((user) => this.renderUserRow(user))}
                    </tbody>
                </table>
            </div>
        )
    }

    renderEmptyState = () => {
        return (
            <div className="empty-state">
                <div className="empty-icon">👥</div>
                <h2>No Users</h2>
                <p>No users found.</p>
            </div>
        )
    }

    render() {
        const { users, loading, success, error } = this.state

        if (loading) {
            return (
                <div className="container">
                    <div className="admin-page">
                        <p>Loading...</p>
                    </div>
                </div>
            )
        }

        return (
            <div className="container">
                <div className="admin-page">
                    <h1>Manage Users</h1>
                    {this.renderAlerts(success, error)}
                    {users.length === 0 ? this.renderEmptyState() : this.renderUsersTable(users)}
                    <div style={{ marginTop: '2rem' }}>
                        <Link to="/admin" className="btn btn-outline">Back to Dashboard</Link>
                    </div>
                </div>
            </div>
        )
    }
}

export default withRouter(Users);

