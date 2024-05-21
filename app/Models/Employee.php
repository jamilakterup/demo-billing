<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $hidden = [];
    protected $fillable = ['name', 'designation_id', 'signature', 'email', 'phone'];
    public function designation()
    {
        return $this->belongsTo(Designation::class)->withDefault([
            'name' => 'not define',
            'email'=>'',
            'phone'=>'',
        ]);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }
}
