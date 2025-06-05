<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class TransPinjam extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    public function anggota()
    {
        return $this->belongsTo(BaseAnggota::class, 'anggota_id');
    }

    public function pinjamItem()
    {
        return $this->hasMany(TransPinjamItem::class, 'pinjam_id');
    }
}
