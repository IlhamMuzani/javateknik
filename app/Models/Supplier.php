<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;


class Supplier extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'kode_supplier',
        'nama_supp',
        'qrcode_supplier',
        'alamat',
        'nama_person',
        'jabatan',
        'telp',
        'fax',
        'hp',
        'email',
        'npwp',
        'nama_bank',
        'atas_nama',
        'norek',
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
        return $getId = DB::table('suppliers')->orderBy('id', 'DESC')->take(1)->get();
    }

    public function pembelian()
    {
        return $this->hasMany(Pembelian::class);
    }

    public function detail_barang()
    {
        return $this->hasMany(Detail_barang::class);
    }
}
