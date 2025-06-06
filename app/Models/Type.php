<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Type extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'kode_type',
        'merek_id',
        'nama_type',
        'qrcode_type',
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

    public function merek()
    {
        return $this->belongsTo(Merek::class);
    }

    public static function getId()
    {
        return $getId = DB::table('types')->orderBy('id', 'DESC')->take(1)->get();
    }
}