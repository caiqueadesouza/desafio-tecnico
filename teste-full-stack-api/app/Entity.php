<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    protected $table = 'entity';

    protected $fillable = [
        'corporate_reason',
        'fantasy_name',
        'cnpj',
        'opening_date',
        'active',
        'regionalId'
    ];

    public function regional()
    {
        return $this->belongsTo(Regional::class, 'regionalId');
    }

    public function specialties()
    {
        return $this->belongsToMany(Specialty::class, 'entity_specialty', 'entityId', 'specialtyId');
    }
}
