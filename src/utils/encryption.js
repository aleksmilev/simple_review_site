const SECRET_KEY = 'reviewhub_secret_key_2024'

export function encrypt(data) {
    const dataStr = typeof data === 'string' ? data : JSON.stringify(data)
    let encrypted = ''
    
    for (let i = 0; i < dataStr.length; i++) {
        const charCode = dataStr.charCodeAt(i)
        const keyChar = SECRET_KEY.charCodeAt(i % SECRET_KEY.length)
        const encryptedChar = charCode ^ keyChar
        encrypted += String.fromCharCode(encryptedChar)
    }
    
    return btoa(encrypted)
}

export function decrypt(encryptedData) {
    try {
        const decoded = atob(encryptedData)
        let decrypted = ''
        
        for (let i = 0; i < decoded.length; i++) {
            const charCode = decoded.charCodeAt(i)
            const keyChar = SECRET_KEY.charCodeAt(i % SECRET_KEY.length)
            const decryptedChar = charCode ^ keyChar
            decrypted += String.fromCharCode(decryptedChar)
        }
        
        try {
            return JSON.parse(decrypted)
        } catch (e) {
            return decrypted
        }
    } catch (e) {
        return null
    }
}

