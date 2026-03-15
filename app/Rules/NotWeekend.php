<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Carbon\Carbon;

class NotWeekend implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return;
        }

        // Daftar hari libur nasional Indonesia tahun 2026 (contoh)
        // Format: Y-m-d
        $holidays = [
            '2026-01-01', // Tahun Baru Masehi
            '2026-02-17', // Isra Mikraj
            '2026-03-20', // Hari Suci Nyepi
            '2026-03-22', // Idul Fitri (Hari 1)
            '2026-03-23', // Idul Fitri (Hari 2)
            '2026-04-03', // Wafat Yesus Kristus
            '2026-05-01', // Hari Buruh Internasional
            '2026-05-14', // Kenaikan Yesus Kristus
            '2026-05-29', // Idul Adha
            '2026-06-01', // Hari Lahir Pancasila
            '2026-06-18', // Tahun Baru Islam
            '2026-08-17', // Hari Kemerdekaan RI
            '2026-08-27', // Maulid Nabi Muhammad SAW
            '2026-12-25', // Hari Raya Natal
        ];

        try {
            $date = Carbon::parse($value);
            
            // Cek Sabtu atau Minggu
            if ($date->isWeekend()) {
                $fail("Jadwal tidak boleh jatuh pada hari Sabtu atau Minggu.");
                return;
            }

            // Cek Hari Libur (Tanggal Merah)
            $dateString = $date->format('Y-m-d');
            if (in_array($dateString, $holidays)) {
                $fail("Jadwal tidak boleh jatuh pada hari libur nasional ($dateString).");
                return;
            }
        } catch (\Exception $e) {
            $fail("Format jadwal tidak valid.");
        }
    }
}

