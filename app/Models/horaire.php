<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class horaire extends Model
{
    protected $table = 'horaires';

    // protected $hidden = ['created_at', 'updated_at','pivot'];

    protected $fillable = [
        'horaire_text',
    	'manager_type',
        'agency_id'
    ];

    // public function groups()
    // {
    //    return $this->belongsToMany(Group::class, 'group_partner', 'partner_id', 'group_id');
       

    // }
    
    public function agency()
    {
       return $this->belongsTo(Agency::class,'agency_id');
       

    }
}
