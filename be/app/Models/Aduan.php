<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

class Aduan extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    protected $table = 'aduans';

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'nomor_aduan',
        'user_uuid',
        'is_anonim',
        'kategori',
        'judul',
        'isi_aduan',
        'lokasi',
        'kecamatan_id',
        'kelurahan_id',
        'foto_1',
        'foto_2',
        'foto_3',
        'tanggal_kejadian',
        'tanggal_pengaduan',
        'status',
        'prioritas',
        'sifat',
        'latitude',
        'longitude',
        'archived_at',
        'rating',
        'ulasan',
        'rated_at',
    ];

    protected $casts = [
        'is_anonim' => 'boolean',
        'tanggal_kejadian' => 'date',
        'tanggal_pengaduan' => 'datetime',
        'archived_at' => 'datetime',
        'rated_at' => 'datetime',
        'rating' => 'integer',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function scopeBelumArsip($query)
    {
        return $query->whereNull('archived_at');
    }

    public function scopeArsip($query)
    {
        return $query->whereNotNull('archived_at');
    }

    public const KATEGORI = [
        'Drainase',
        'Sungai/Kali',
        'Tanggul',
        'Polder/Kolam Retensi',
        'Irigasi',
        'Pematusan',
        'Bangunan/Sarana SDA',
        'Banjir/Genangan',
        'Lainnya',
    ];

    public const STATUS = [
        'menunggu',
        'diverifikasi',
        'diproses',
        'selesai',
        'ditolak',
    ];

    public const PRIORITAS = [
        'rendah',
        'sedang',
        'tinggi',
        'darurat',
    ];

    public const SIFAT = [
        'biasa',
        'penting',
        'segera',
        'rahasia',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($aduan) {
            if (!$aduan->uuid) {
                $aduan->uuid = (string) Str::uuid();
            }

            if (!$aduan->nomor_aduan) {
                $aduan->nomor_aduan = static::generateNomorAduan();
            }

            if (!$aduan->tanggal_pengaduan) {
                $aduan->tanggal_pengaduan = now();
            }
        });
    }

    public const DUPLIKAT_RADIUS_M = 500;
    public const DUPLIKAT_HARI = 30;
    public const DUPLIKAT_STATUS = ['menunggu', 'diverifikasi', 'diproses'];

    /**
     * Cari aduan sejenis: kategori sama + (radius dekat ATAU kecamatan sama)
     * + waktu berdekatan + masih aktif (belum selesai/ditolak).
     */
    public static function cariDuplikat(
        ?string $kategori,
        $latitude = null,
        $longitude = null,
        $kecamatanId = null,
        $tanggal = null,
        ?string $kecualiUuid = null,
        int $limit = 5
    ) {
        if (!$kategori) {
            return collect();
        }

        $punyaKoordinat = is_numeric($latitude) && is_numeric($longitude)
            && abs($latitude) <= 90 && abs($longitude) <= 180;

        if (!$punyaKoordinat && !$kecamatanId) {
            return collect();
        }

        try {
            $acuan = $tanggal ? Carbon::parse($tanggal) : now();
        } catch (\Throwable) {
            $acuan = now();
        }

        $bawah = $acuan->copy()->subDays(static::DUPLIKAT_HARI)->startOfDay();
        $atas = $acuan->copy()->addDay()->endOfDay();

        $query = static::query()
            ->where('kategori', $kategori)
            ->whereIn('status', static::DUPLIKAT_STATUS)
            ->where(function ($q) use ($bawah, $atas) {
                $q->whereBetween('tanggal_kejadian', [$bawah->toDateString(), $atas->toDateString()])
                    ->orWhere(function ($q2) use ($bawah) {
                        $q2->whereNull('tanggal_kejadian')
                            ->where('created_at', '>=', $bawah);
                    });
            });

        if ($punyaKoordinat) {
            $radius = static::DUPLIKAT_RADIUS_M;
            $dLat = $radius / 111320;
            $dLng = $radius / (111320 * max(0.2, cos(deg2rad((float) $latitude))));
            $query->whereBetween('latitude', [(float) $latitude - $dLat, (float) $latitude + $dLat])
                ->whereBetween('longitude', [(float) $longitude - $dLng, (float) $longitude + $dLng]);
        } else {
            $query->where('kecamatan_id', $kecamatanId);
        }

        if ($kecualiUuid) {
            $query->where('uuid', '!=', $kecualiUuid);
        }

        return $query->orderByDesc('tanggal_pengaduan')->limit($limit)->get();
    }

    public static function generateNomorAduan(): string
    {
        $prefix = 'ADU-' . now()->format('Ymd');

        $last = static::where('nomor_aduan', 'like', $prefix . '-%')
            ->orderByDesc('nomor_aduan')
            ->first();

        $next = 1;

        if ($last && preg_match('/-(\d+)$/', $last->nomor_aduan, $m)) {
            $next = ((int) $m[1]) + 1;
        }

        return $prefix . '-' . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class);
    }

    public function tindakLanjuts()
    {
        return $this->hasMany(AduanTindakLanjut::class, 'aduan_uuid', 'uuid')
            ->orderByDesc('tanggal');
    }

    public function fotoUrl(?string $foto): ?string
    {
        return $foto ? asset('storage/' . $foto) : null;
    }

    public function getFoto1UrlAttribute(): ?string
    {
        return $this->fotoUrl($this->foto_1);
    }

    public function getFoto2UrlAttribute(): ?string
    {
        return $this->fotoUrl($this->foto_2);
    }

    public function getFoto3UrlAttribute(): ?string
    {
        return $this->fotoUrl($this->foto_3);
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->uuid,
            'nomor_aduan' => $this->nomor_aduan,
            'judul' => $this->judul,
            'isi_aduan' => strip_tags($this->isi_aduan),
            'lokasi' => $this->lokasi,
            'kategori' => $this->kategori,
            'status' => $this->status,
            'prioritas' => $this->prioritas,
            'sifat' => $this->sifat,
            'kecamatan' => $this->kecamatan?->name,
            'kelurahan' => $this->kelurahan?->name,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'tanggal_kejadian' => $this->tanggal_kejadian?->timestamp,
            'tanggal_pengaduan' => $this->tanggal_pengaduan?->timestamp,
            'is_anonim' => $this->is_anonim,
            'user_name' => !$this->is_anonim ? $this->user?->name : 'Anonim',
            'rating' => $this->rating,
            'created_at' => $this->created_at?->timestamp,
            'updated_at' => $this->updated_at?->timestamp,
        ];
    }

    public function shouldBeSearchable(): bool
    {
        return !$this->is_anonim && $this->status !== 'ditolak';
    }
}
