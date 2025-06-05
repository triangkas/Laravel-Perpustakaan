<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class BaseAnggota extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    public static function getComboAnggota()
    {
        $result = self::selectRaw("id, CONCAT(no_anggota, ' | ', nama) as name")
                        ->orderBy('nama', 'asc')
                        ->get();
        return $result;
    }

    public function pinjam()
    {
        return $this->hasMany(TransPinjam::class, 'anggota_id');
    }
}
