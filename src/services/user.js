import TokenStorage from './token.js'
import { encrypt, decrypt } from '../utils/encryption.js'

class UserService {
    static USER_KEY = 'reviewhub_user'

    static setUser(user) {
        if (user) {
            const encrypted = encrypt(user)
            localStorage.setItem(UserService.USER_KEY, encrypted)
        } else {
            localStorage.removeItem(UserService.USER_KEY)
        }
    }

    static getUser() {
        const encryptedUser = localStorage.getItem(UserService.USER_KEY)
        if (encryptedUser) {
            try {
                const user = decrypt(encryptedUser)
                return user.user
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
        return user && user.role == 'admin'
    }

    static logout() {
        UserService.removeUser()
        TokenStorage.removeToken()
    }
}

export default UserService

