<?php

namespace App\Models;

use App\Models\Traits\RelatesToTeams;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Thing extends Model
{
    use HasFactory,
        RelatesToTeams,
        HasRecursiveRelationships,
        Searchable;

    public $asYouType = true;

    protected $fillable = [
        'parent_id'
    ];

    public static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });

        static::deleting(function ($model) {
            optional($model->thingable)->delete();
            $model->descendants->each->delete();
        });
    }

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'team_id' => $this->team_id,
            'name' => $this->thingable->name,
            'path' => $this->ancestorsAndSelf->pluck('thingable.name')->reverse()->join('/')
        ];
    }

    public function thingable()
    {
        return $this->morphTo();
    }


}
