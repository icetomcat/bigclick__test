<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class NestedSet extends Model
{
    use NodeTrait;

    protected $fillable = [
        'title'
    ];
}
