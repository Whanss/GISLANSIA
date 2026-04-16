<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Notifikasi extends Model
{
    use HasUuids;

    protected $primaryKey = "id";

    protected $fillable = [
        "type",
        "notifiable_type",
        "notifiable_id",
        "data",
        "read_at",
    ];

    protected $casts = [
        "data" => "array",
        "read_at" => "datetime",
    ];

    public function notifiable()
    {
        return $this->morphTo();
    }
}
