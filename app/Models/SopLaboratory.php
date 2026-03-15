<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SopLaboratory extends Model
{
    use HasFactory;

    protected $fillable = [
        'laboratorium',
        'file_path',
    ];
}
