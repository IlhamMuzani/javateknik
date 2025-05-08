<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Harga extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable =
    [
        'kategori',
        'kode_harga',
        'nama_harga',
        'qrcode_harga',
        'harga_a',
        'harga_b',
        'harga_c',
        'harga_d',
        'harga_e',
        'keterangan',
        'tanggal_awal',
    ];

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable('*');
    }

    public static function getId()
    {
        return $getId = DB::table('hargas')->orderBy('id', 'DESC')->take(1)->get();
    }
}