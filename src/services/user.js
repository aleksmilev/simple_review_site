import TokenStorage from './token.js'
import { encrypt, decrypt } from './encryption.js'
import ApiRequest from './api.js'

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

    static async fetchAndUpdateUser() {
        try {
            const userRequest = new ApiRequest({
                url: '/user/user',
                method: 'GET',
                params: {}
            })
            
            const userResponse = await userRequest.exec()
            
            if (userResponse.status === 'OK' && userResponse.response) {
                UserService.setUser(userResponse.response)
                return userResponse.response
            }
            return null
        } catch (error) {
            console.error('Failed to fetch user data:', error)
            return null
        }
    }
}

export default UserService

