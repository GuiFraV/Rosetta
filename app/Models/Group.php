<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $table = 'groups';

    // protected $hidden = ['created_at', 'updated_at','pivot'];

    protected $fillable = [
        'message'
    ];

    public function partners()
    {
        return $this->belongsToMany(Partner::class, 'group_partner', 'group_id', 'partner_id')->where('status', 1);
    }

    public function getPartnersCount()
    {
        return count($this->partners);
    }
}
