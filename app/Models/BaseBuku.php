<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class BaseBuku extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    public static function getComboBuku()
    {
        $result = self::selectRaw("id, CONCAT(judul_buku, ' | Penerbit : ', penerbit, ' | Stock : ', stock) as name")
                        ->orderBy('judul_buku', 'asc')
                        ->get();
        return $result;
    }
}
