<?php

namespace App\Repositories\Connect;

use App\Models\Connect\AppsCredential;

class AppsCredentialRepository
{
    public function __construct(private AppsCredential $credentials)
    {
    }

    public function getListByUserId($userId)
    {
        $data = $this->credentials->query()
            ->where('user_id', $userId)
            ->whereNotNull(['spass_id', 'spass_value'])
            ->get(['id', 'merchant_id']);

        return $data;
    }

    public function revoke($secret)
    {
        $revoked = $this->credentials->query()
            ->where('client_secret', $secret)
            ->update(['revoked' => true]);

        return $revoked;
    }

    public function encryptToken($data)
    {
        return $this->stringEncryption('encrypt', json_encode($data));
    }

    public function decryptToken($data)
    {
        return $this->stringEncryption('decrypt', json_encode($data));
    }

    private function stringEncryption($action, $string)
    {
        $output = false;
        $encrypt_method = 'AES-256-CBC';            // Default
        $secret_key = 'Nusapay#Key!';               // Change the key!
        $secret_iv = '!NP@_$2';                     // Change the init vector!

        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } elseif ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }
}
