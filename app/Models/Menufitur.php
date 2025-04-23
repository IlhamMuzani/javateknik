<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menufitur extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable =
    [
        'kategori',
        'nama',
        'route',
    ];

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable('*');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_menus', 'menu_id', 'user_id');
    }
}