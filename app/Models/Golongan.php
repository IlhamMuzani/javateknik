<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Golongan extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'kode_golongan',
        'nama_golongan',
        'qrcode_golongan',
        'tanggal_awal',
        'tanggal_akhir',
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
        return $getId = DB::table('golongans')->orderBy('id', 'DESC')->take(1)->get();
    }

    public function pelanggan()
    {
        return $this->hasMany(Pelanggan::class);
    }
}
