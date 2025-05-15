<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Detail_pelunasanpenjualan extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'faktur_pelunasanpenjualan_id',
        'penjualan_id',
        'kode_penjualan',
        'tanggal_penjualan',
        'status',
        'total',
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
    public function faktur_pelunasanpenjualan()
    {
        return $this->belongsTo(Faktur_pelunasanpenjualan::class);
    }
    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class);
    }
}
