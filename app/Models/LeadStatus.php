<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadStatus extends Model
{
    use HasFactory;
    protected $fillable = ['consultant', 'comment', 'status', 'followup'];

    public function lead_collection()
    {
        return $this->belongsTo(LeadCollection::class);
    }
}
