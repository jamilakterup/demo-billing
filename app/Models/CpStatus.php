<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CpStatus extends Model
{
    use HasFactory;
    protected $fillable = ['consultant', 'comment', 'status', 'followup'];

    public function cp_lead()
    {
        return $this->belongsTo(CpLead::class);
    }
}
