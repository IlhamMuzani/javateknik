<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Detailpenjualan extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'penjualan_id',
        'barang_id',
        'kode_barang',
        'qrcode_barang',
        'nama_barang',
        'jumlah',
        'satuan_id',
        'diskon',
        'harga',
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

    public function Penjualan()
    {
        return $this->belongsTo(Penjualan::class);
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }
}
