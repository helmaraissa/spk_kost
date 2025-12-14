<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCriteria extends Model
{
    use HasFactory;

    protected $table = 'sub_criterias';
    protected $primaryKey = 'sub_id';

    protected $fillable = [
        'criteria_id',
        'name', 
        'value'
    ];

    public function criteria()
    {
        return $this->belongsTo(Criteria::class, 'criteria_id', 'criteria_id');
    }
}