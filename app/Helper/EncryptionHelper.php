<?php

namespace App\Helper;

class EncryptionHelper
{
    public static function encrypt($value, string $passphrase)
    {
        $salt = openssl_random_pseudo_bytes(8);
        $salted = '';
        $dx = '';
        while (strlen($salted) < 48) {
            $dx = md5($dx . $passphrase . $salt, true);
            $salted .= $dx;
        }
        $key = substr($salted, 0, 32);
        $iv = substr($salted, 32, 16);
        $encrypted_data = openssl_encrypt(json_encode($value), 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
        $data = ["ct" => base64_encode($encrypted_data), "iv" => bin2hex($iv), "s" => bin2hex($salt)];
        return json_encode($data);
    }

    public static function decrypt($jsonStr, string $passphrase)
    {
        $data = json_decode($jsonStr, true);
        if (!is_array($data) || !isset($data["s"], $data["iv"], $data["ct"])) {
            return null; // or throw an exception
        }
        $salt = hex2bin($data["s"]);
        $iv = hex2bin($data["iv"]);
        $ct = base64_decode($data["ct"]);
        $salted = '';
        $dx = '';
        while (strlen($salted) < 48) {
            $dx = md5($dx . $passphrase . $salt, true);
            $salted .= $dx;
        }
        $key = substr($salted, 0, 32);
        $decrypted_data = openssl_decrypt($ct, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
        return json_decode($decrypted_data, true);
    }
}
