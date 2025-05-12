<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penjualan extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable =
    [
        'kategori',
        'kode_penjualan',
        'kode_qrcode',
        'qrcode_penjualan',
        'pelanggan_id',
        'total_harga',
        'ppn',
        'nominal_pembayaran',
        'grand_total',
        'tanggal',
        'tanggal_awal',
        'tanggal_akhir',
        'status',
        'status_notif',
    ];

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable('*');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function detailpenjualan()
    {
        return $this->hasMany(Detailpenjualan::class);
    }

    public static function getId()
    {
        return $getId = DB::table('penjualans')->orderBy('id', 'DESC')->take(1)->get();
    }
}
