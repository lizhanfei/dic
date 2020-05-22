<?php

declare(strict_types=1);

namespace App\Model;

use App\Model\Model;

class Word extends Model
{
    protected $table = 'word';

    protected $fillable = ['id', 'word', 'from_system', 'type', 'add_time'];

    public $timestamps = false;
}