<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'client_name',
        'address',
        'due_date',
        'contact_phone',
        'contact_email',
        'notes',
        'status'
    ];

    public function rooms()
{
    return $this->hasMany(\App\Models\Room::class)->orderBy('sort_order')->orderBy('id');
}

}
