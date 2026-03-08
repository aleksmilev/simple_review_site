<?php

class ValidationApi
{
    private static $encryptionMethod = 'AES-256-CBC';

    private static function getEncryptionKey()
    {
        return $_ENV['API_ENCRYPTION_KEY'] ?? 'your-secret-key-change-this-in-production';
    }

    private static function getEncryptionMethod()
    {
        return $_ENV['API_ENCRYPTION_METHOD'] ?? self::$encryptionMethod;
    }

    public static function validateAdminUser()
    {
        $token = self::getToken();
        if (empty($token)) {
            return false;
        }

        $tokenData = self::decryptToken($token);
        if (!$tokenData || !isset($tokenData['role'])) {
            return false;
        }

        return $tokenData['role'] == 'admin';
    }

    public static function encryptToken($data)
    {
        $encryptionKey = self::getEncryptionKey();
        $encryptionMethod = self::getEncryptionMethod();
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($encryptionMethod));
        $encrypted = openssl_encrypt(json_encode($data), $encryptionMethod, $encryptionKey, 0, $iv);
        return base64_encode($encrypted . '::' . $iv);
    }

    public static function decryptToken($token)
    {
        $encryptionKey = self::getEncryptionKey();
        $encryptionMethod = self::getEncryptionMethod();
        $data = base64_decode($token);
        list($encrypted_data, $iv) = explode('::', $data, 2);
        $decrypted = openssl_decrypt($encrypted_data, $encryptionMethod, $encryptionKey, 0, $iv);
        return json_decode($decrypted, true);
    }

    public static function getToken()
    {
        $headers = getallheaders();
        if (isset($headers['Authorization'])) {
            $authHeader = $headers['Authorization'];
            if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
                return $matches[1];
            }
        }

        if (isset($_GET['token'])) {
            return $_GET['token'];
        }

        return "";
    }
}