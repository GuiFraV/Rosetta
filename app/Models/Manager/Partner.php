<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $table = 'partners';

    // protected $hidden = ['created_at', 'updated_at','pivot'];

    protected $fillable = [
        'manager_id',
    	'name',
        'company',
    	'origin',
        'phone',
    	'email',
        'type',
        'status'
    ];

    // public function groups()
    // {
    //    return $this->belongsToMany(Group::class, 'group_partner', 'partner_id', 'group_id');
       

    // }
    
    public function manager()
    {
       return $this->belongsTo(Manager::class);
       

    }
}
