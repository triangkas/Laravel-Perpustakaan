<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class TransKembali extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    public function pinjam()
    {
        return $this->belongsTo(TransPinjam::class, 'pinjam_id');
    }

    public function kembaliItem()
    {
        return $this->hasMany(TransKembaliItem::class, 'kembali_id');
    }
}
