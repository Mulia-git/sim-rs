<?php

use App\Models\BpjsConfig;
use LZCompressor\LZString;

if (!function_exists('vclaim_config')) {

    function vclaim_config()
    {
        return BpjsConfig::where('is_active', 1)->latest()->first();
    }
}

if (!function_exists('vclaim_headers')) {

    function vclaim_headers()
    {
        $cfg = vclaim_config();

        if (!$cfg) return null;

        date_default_timezone_set('UTC');
        $timestamp = (string) time();

        $signature = base64_encode(
            hash_hmac('sha256',
                $cfg->cons_id . "&" . $timestamp,
                $cfg->secret_key,
                true
            )
        );

        return [
            'X-cons-id'   => $cfg->cons_id,
            'X-timestamp' => $timestamp,
            'X-signature' => $signature,
            'user_key'    => $cfg->user_key,
            'secret_key'  => $cfg->secret_key,
            'base_url'    => $cfg->base_url
        ];
    }
}

if (!function_exists('vclaim_decrypt')) {

        function vclaim_decrypt($cipher, $consId, $secretKey, $timestamp)
        {
            $key = $consId . $secretKey . $timestamp;
            $key_hash = hex2bin(hash('sha256', $key));
            $iv = substr($key_hash, 0, 16);

            $decrypt = openssl_decrypt(
                base64_decode($cipher),
                'AES-256-CBC',
                $key_hash,
                OPENSSL_RAW_DATA,
                $iv
            );

            return LZString::decompressFromEncodedURIComponent($decrypt);
        }
}

if (!function_exists('vclaim_get')) {

    function vclaim_get($endpoint)
    {
        $h = vclaim_headers();
        if (!$h) return null;

        $url = rtrim($h['base_url'], '/') . '/vclaim-rest/' . ltrim($endpoint, '/');

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'X-cons-id: ' . $h['X-cons-id'],
                'X-timestamp: ' . $h['X-timestamp'],
                'X-signature: ' . $h['X-signature'],
                'user_key: ' . $h['user_key'],
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        $json = json_decode($response, true);

        if (($json['metaData']['code'] ?? null) != 200) {
            return $json;
        }

        $decrypt = vclaim_decrypt(
            $json['response'],
            $h['X-cons-id'],
            $h['secret_key'],
            $h['X-timestamp']
        );

        return [
            'metaData' => $json['metaData'],
            'response' => json_decode($decrypt, true)
        ];
    }
}
if (!function_exists('vclaim_post')) {

    function vclaim_post($endpoint, $payload = [])
    {
        $h = vclaim_headers();
        if (!$h) return null;

        $url = rtrim($h['base_url'], '/') . '/vclaim-rest/' . ltrim($endpoint, '/');

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'X-cons-id: ' . $h['X-cons-id'],
                'X-timestamp: ' . $h['X-timestamp'],
                'X-signature: ' . $h['X-signature'],
                'user_key: ' . $h['user_key'],
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        return vclaim_handle_response($response, $h);
    }
}
if (!function_exists('vclaim_put')) {

    function vclaim_put($endpoint, $payload = [])
    {
        $h = vclaim_headers();
        if (!$h) return null;

        $url = rtrim($h['base_url'], '/') . '/vclaim-rest/' . ltrim($endpoint, '/');

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'X-cons-id: ' . $h['X-cons-id'],
                'X-timestamp: ' . $h['X-timestamp'],
                'X-signature: ' . $h['X-signature'],
                'user_key: ' . $h['user_key'],
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        return vclaim_handle_response($response, $h);
    }
}
if (!function_exists('vclaim_delete')) {

    function vclaim_delete($endpoint)
    {
        $h = vclaim_headers();
        if (!$h) return null;

        $url = rtrim($h['base_url'], '/') . '/vclaim-rest/' . ltrim($endpoint, '/');

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_HTTPHEADER => [
                'X-cons-id: ' . $h['X-cons-id'],
                'X-timestamp: ' . $h['X-timestamp'],
                'X-signature: ' . $h['X-signature'],
                'user_key: ' . $h['user_key'],
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        return vclaim_handle_response($response, $h);
    }
}
if (!function_exists('vclaim_handle_response')) {

    function vclaim_handle_response($response, $h)
    {
        $json = json_decode($response, true);

        if (($json['metaData']['code'] ?? null) != 200) {
            return $json;
        }

        if (!isset($json['response'])) {
            return $json;
        }

        $decrypt = vclaim_decrypt(
            $json['response'],
            $h['X-cons-id'],
            $h['secret_key'],
            $h['X-timestamp']
        );

        return [
            'metaData' => $json['metaData'],
            'response' => json_decode($decrypt, true)
        ];
    }
}