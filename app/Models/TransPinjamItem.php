<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class TransPinjamItem extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    public function buku()
    {
        return $this->belongsTo(BaseBuku::class, 'buku_id');
    }
}
