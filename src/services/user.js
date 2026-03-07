import TokenStorage from './token.js'

class UserService {
    static USER_KEY = 'reviewhub_user'

    static setUser(user) {
        if (user) {
            localStorage.setItem(UserService.USER_KEY, JSON.stringify(user))
        } else {
            localStorage.removeItem(UserService.USER_KEY)
        }
    }

    static getUser() {
        const userStr = localStorage.getItem(UserService.USER_KEY)
        if (userStr) {
            try {
                return JSON.parse(userStr)
            } catch (e) {
                return null
            }
        }
        return null
    }

    static removeUser() {
        localStorage.removeItem(UserService.USER_KEY)
    }

    static isLoggedIn() {
        return TokenStorage.hasToken()
    }

    static isAdmin() {
        const user = UserService.getUser()
        return user && user.role === 'admin'
    }

    static logout() {
        UserService.removeUser()
        TokenStorage.removeToken()
    }
}

export default UserService

