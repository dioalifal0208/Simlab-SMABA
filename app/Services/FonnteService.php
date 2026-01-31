<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    /**
     * Mengirim satu pesan WhatsApp menggunakan Fonnte.
     *
     * @param string $target  Nomor tujuan (format internasional, mis. 628xxxx)
     * @param string $message Isi pesan WhatsApp
     * @param array<string, mixed> $extra  Opsi tambahan (jika dibutuhkan)
     */
    public function sendMessage(string $target, string $message, array $extra = []): bool
    {
        $token = config('services.fonnte.token');
        $endpoint = config('services.fonnte.endpoint', 'https://api.fonnte.com/send');

        if (empty($token)) {
            Log::warning('Fonnte token is not configured; skipping WhatsApp send.');
            return false;
        }

        try {
            $payload = array_merge([
                'target'  => $target,
                'message' => $message,
            ], $extra);

            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->asForm()->post($endpoint, $payload);

            if (! $response->successful()) {
                Log::error('Fonnte send failed', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);

                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('Fonnte send exception', [
                'message' => $e->getMessage(),
            ]);

            return false;
        }
    }
}

