<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadCollection extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'email', 'phone', 'source', 'status'];

    public function lead_status()
    {
        return $this->hasMany(LeadStatus::class);
    }
}
