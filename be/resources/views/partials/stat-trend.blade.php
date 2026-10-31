@php
    $growth = (float) ($growth ?? 0);
    $daysDiff = (int) ($daysDiff ?? 30);
    // growth dibulatkan 1 desimal lalu hilangkan .0 biar layout tidak melebar & tanpa koma
    $growthAbs = abs($growth);
    $growthFormatted = rtrim(rtrim(number_format($growthAbs, 1, '.', ''), '0'), '.');
    // periode label simpel tanpa desimal
    $periodeLabel = $daysDiff === 1
        ? 'dibanding kemarin'
        : "dibanding {$daysDiff} hari sebelumnya";
@endphp
@if ($growth > 0.05)
    <div class="stat-trend" title="{{ $periodeLabel }}">
        <i class="bi bi-arrow-up-right text-success"></i>
        <span class="text-success fw-medium">Naik {{ $growthFormatted }}%</span>
        <span class="text-muted small">{{ $periodeLabel }}</span>
    </div>
@elseif ($growth < -0.05)
    <div class="stat-trend" title="{{ $periodeLabel }}">
        <i class="bi bi-arrow-down-right text-danger"></i>
        <span class="text-danger fw-medium">Turun {{ $growthFormatted }}%</span>
        <span class="text-muted small">{{ $periodeLabel }}</span>
    </div>
@else
    <div class="stat-trend text-muted" title="{{ $periodeLabel }}">
        <i class="bi bi-dash"></i>
        <span>Stabil 0%</span>
        <span class="small">{{ $periodeLabel }}</span>
    </div>
@endif