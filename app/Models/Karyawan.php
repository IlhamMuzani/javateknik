<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Karyawan extends Model
{
    use LogsActivity;
    use HasFactory;
    protected $fillable = [
        'kode_karyawan',
        'departemen_id',
        'qrcode_karyawan',
        'no_ktp',
        'no_sim',
        'nama_bank',
        'atas_nama',
        'norek',
        'nama_lengkap',
        'nama_kecil',
        'gender',
        'tanggal_lahir',
        'tanggal_gabung',
        'tanggal_keluar',
        'telp',
        'jabatan',
        'alamat',
        'alamat2',
        'alamat3',
        'gmail',
        'gambar',
        'pembayaran',
        'gaji',
        'tabungan',
        'kasbon',
        'deposit',
        'bayar_kasbon',
        'bpjs',
        'ft_ktp',
        'ft_sim',
        'potongan_ke',
        'potongan_backup',
        'kasbon_backup',
        'status',
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
        return $getId = DB::table('karyawans')->orderBy('id', 'DESC')->take(1)->get();
    }

    public function departemen()
    {
        return $this->belongsTo(Departemen::class);
    }

    public function user()
    {
        return $this->hasMany(User::class);
    }
}