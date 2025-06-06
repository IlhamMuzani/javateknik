<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'kategori',
        'kode_last',
        'merek_id',
        'type_id',
        'bagian_id',
        'satuan_id',
        'kode_barang',
        'kode_qrcode',
        'nama_barang',
        'qrcode_barang',
        'jumlah',
        'spesifikasi',
        'keterangan',
        'harga_barang',
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

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function bagian()
    {
        return $this->belongsTo(Bagian::class);
    }

    public function harga()
    {
        return $this->hasMany(Harga::class);
    }

    public static function getId()
    {
        return $getId = DB::table('barangs')->orderBy('id', 'DESC')->take(1)->get();
    }
}