<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'latitude',
        'longitude',
    ];

    /**
     * Searceable attributes.
     *
     * @return array<int, string>
     */
    public static final function searchableAttributes() {
        return ['name', 'phone', 'email'];
    }
}
