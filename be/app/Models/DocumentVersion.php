<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentVersion extends Model
{
    use HasFactory, HasUuid;

    protected $table = 'document_versions';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['uuid', 'document_uuid', 'title', 'file', 'file_url', 'thumbnail', 'keterangan', 'user_uuid', 'versi'];

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_uuid', 'uuid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }
}
