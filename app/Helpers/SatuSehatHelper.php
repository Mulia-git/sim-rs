<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

if (!function_exists('satu_get_config')) {
    function satu_get_config($tenantId = null)
    {
        return DB::table('satu_sehat_config')
            ->where('is_active', 1)
            ->orderByDesc('id')
            ->first();
    }
}

if (!function_exists('satu_get_token')) {
    function satu_get_token($cfg)
    {
        $cacheKey = 'satusehat_token_' . $cfg->id;

        return Cache::remember($cacheKey, 1700, function () use ($cfg) {

            $response = Http::asForm()->post(
                rtrim($cfg->auth_url, '/') . '/accesstoken?grant_type=client_credentials',
                [
                    'client_id'     => $cfg->client_id,
                    'client_secret' => $cfg->client_secret,
                ]
            );

            if (!$response->successful()) {
                return null;
            }

            return $response->json()['access_token'] ?? null;
        });
    }
}

if (!function_exists('satu_request')) {
    function satu_request($method, $path, $payload = null)
    {
        $cfg = satu_get_config();
        if (!$cfg) {
            return ['success'=>false,'error'=>'Config tidak ditemukan'];
        }

        $token = satu_get_token($cfg);
        if (!$token) {
            return ['success'=>false,'error'=>'Token gagal didapatkan'];
        }

        $url = rtrim($cfg->base_url, '/') . '/' . ltrim($path, '/');

        $response = Http::withToken($token)
            ->accept('application/fhir+json')
            ->send($method, $url, [
                'json' => $payload
            ]);

        return [
            'success' => $response->successful(),
            'status'  => $response->status(),
            'data'    => $response->json()
        ];
    }
}

if (!function_exists('satu_get')) {
    function satu_get($path) {
        return satu_request('GET', $path);
    }
}

if (!function_exists('satu_post')) {
    function satu_post($path, $payload) {
        return satu_request('POST', $path, $payload);
    }
}

if (!function_exists('satu_put')) {
    function satu_put($path, $payload) {
        return satu_request('PUT', $path, $payload);
    }
}

if (!function_exists('satu_delete')) {
    function satu_delete($path) {
        return satu_request('DELETE', $path);
    }
}

if (!function_exists('satu_patient_by_nik')) {
    function satu_patient_by_nik($nik)
    {
        $system = 'https://fhir.kemkes.go.id/id/nik';
        $identifier = rawurlencode($system . '|' . $nik);

        $res = satu_get("Patient?identifier={$identifier}");

        if ($res['success'] && !empty($res['data']['entry'][0]['resource']['id'])) {

            $ihs = $res['data']['entry'][0]['resource']['id'];

            return [
                'success'    => true,
                'ihs_number' => $ihs,
                'resource'   => $res['data']['entry'][0]['resource']
            ];
        }

        return [
            'success' => false,
            'message' => 'Patient tidak ditemukan'
        ];
    }
}