<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background: #f2f2f2;
            text-align: center;
        }

        td {
            vertical-align: top;
        }
    </style>
</head>

<body>
    <h3 style="text-align: center; margin-bottom: 5px;">Laporan Data Pengguna</h3>
    <p style="text-align: center; margin: 0;">
        Dicetak pada: {{ now()->translatedFormat('d F Y') }}
    </p>

    {{-- Info filter --}}
    @if (($filters['tahun'] ?? null) || ($filters['start_date'] ?? null) || ($filters['end_date'] ?? null))
        <p style="margin-top: 10px; font-size: 11px;">
            <strong>Filter:</strong>
            @if (!empty($filters['tahun']))
                Tahun <u>{{ $filters['tahun'] }}</u>
            @endif
            @if (!empty($filters['start_date']))
                Dari <u>{{ \Carbon\Carbon::parse($filters['start_date'])->format('d/m/Y') }}</u>
            @endif
            @if (!empty($filters['end_date']))
                Sampai <u>{{ \Carbon\Carbon::parse($filters['end_date'])->format('d/m/Y') }}</u>
            @endif
        </p>
    @endif


    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>No. Handphone</th>
                <th>Status Verifikasi</th>
                <th>Tanggal Daftar</th>
            </tr>
        </thead>
        <tbody>
@php($safe = fn($v) => is_string($v) && preg_match('/^[\s\t]*[=+\-@]/u', $v) ? "'" . $v : $v)
            @foreach ($pengguna as $index => $user)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $safe($user->name) }}</td>
                    <td>{{ $safe($user->email) }}</td>
                    <td>{{ $safe($user->no_hp ?? '-') }}</td>
                    <td>{{ $user->email_verified_at ? 'Terverifikasi' : 'Belum Verifikasi' }}</td>
                    <td>{{ $user->created_at->format('d-m-Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
