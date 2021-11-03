<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class files extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'parent_id',
        'user_id',
        'type',
        'file_path',
        'file_size',
        'file_type'
    ];
    public function children()
    {
        return $this->hasMany(files::class, 'parent_id', 'id');
    }
}
