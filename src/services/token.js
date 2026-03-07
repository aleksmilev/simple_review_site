const TOKEN_KEY = 'reviewhub_token'

class TokenStorage {
    static setToken(token) {
        if (token) {
            localStorage.setItem(TOKEN_KEY, token)
        } else {
            localStorage.removeItem(TOKEN_KEY)
        }
    }

    static getToken() {
        return localStorage.getItem(TOKEN_KEY)
    }

    static removeToken() {
        localStorage.removeItem(TOKEN_KEY)
    }

    static hasToken() {
        return !!this.getToken()
    }
}

export default TokenStorage

