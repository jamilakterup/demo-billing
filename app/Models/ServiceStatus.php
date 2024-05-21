<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceStatus extends Model
{
    use HasFactory;
    protected $fillable = ['consultant', 'comment', 'status', 'followup'];

    public function service_lead()
    {
        return $this->belongsTo(ServiceLead::class);
    }
}
