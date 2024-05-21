<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CpLead extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'email', 'phone', 'source', 'status'];

    public function cp_status()
    {
        return $this->hasMany(CpStatus::class);
    }
}
