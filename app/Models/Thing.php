<?php

namespace App\Models;

use App\Models\Traits\RelatesToTeams;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Thing extends Model
{
    use HasFactory, RelatesToTeams, HasRecursiveRelationships;

    protected $fillable = [
        'parent_id'
    ];

    public static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function thingable()
    {
        return $this->morphTo();
    }


}
