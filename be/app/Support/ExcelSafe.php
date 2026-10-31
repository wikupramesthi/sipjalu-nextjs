<?php

namespace App\Support;

/**
 * Netralkan formula injection (CSV/Excel formula injection / CWE-1236).
 *
 * Sel spreadsheet yang diawali = + - @ (atau diawali spasi/tab lalu simbol
 * itu) dieksekusi sebagai formula saat berkas dibuka di Excel/LibreOffice.
 * Prefix dengan apostrof sehingga selalu diperlakukan sebagai teks.
 */
final class ExcelSafe
{
    public static function text(mixed $value): mixed
    {
        if (! is_string($value)) {
            return $value;
        }
        if ($value === '') {
            return $value;
        }
        // Awalan berbahaya, termasuk setelah spasi/tab (bypass klasik).
        if (preg_match('/^[\s\t\x{FEFF}]*[=+\-@]/u', $value)) {
            return "'" . $value;
        }

        return $value;
    }

    /** Terapkan ke satu baris array numerik/assosiatif. */
    public static function row(array $row): array
    {
        foreach ($row as $k => $v) {
            $row[$k] = self::text($v);
        }

        return $row;
    }
}
