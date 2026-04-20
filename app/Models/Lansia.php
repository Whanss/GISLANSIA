<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Lansia extends Model
{
    use HasUuids;

    protected $keyType = "string";
    public $incrementing = false;

    protected $fillable = [
        "nik",
        "nama",
        "note",
        "foto",
        "status",
        "user_id",
        "pendata", 
        "rw",
        "rt",
        "alamat",
        "desa",
        "kecamatan",
        "kabupaten",
        "provinsi",
        "umur",
        "tanggal_lahir",
        "latitude",
        "longitude",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notifikasi()
    {
        return $this->morphMany(Notifikasi::class, "notifiable");
    }
}
