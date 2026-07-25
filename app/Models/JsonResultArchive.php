<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JsonResultArchive extends Model
{
    use HasFactory;

    protected $fillable = ['ea_result_id', 'data'];

    protected $casts = [
        'data' => 'array',
    ];
}
