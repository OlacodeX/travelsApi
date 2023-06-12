<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Travel extends Model
{
    use HasFactory, HasUuids, Sluggable;

    protected $table = "travels";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name','is_public','slug','description','number_of_days'];

     /**
     * Get all of the tours for the Travel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tours(): HasMany
    {
        return $this->hasMany(Tour::class);
    }

    /**
     * This function tells the sluggable package which column should be used fo generating the slug
     *
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

     /**
     * This is an accessor used to modify columns before or after saving them in the db. In our case the numberOfNights doesn't necessarily needs to be saved in the db but it's calculated off a column in the db hence we use only the get method of the accessor. 
     *
     */

    public function numberOfNights(): Attribute
    {
        return Attribute::make(
            // I am passing the attribute as a second parameter because I am getting the number of nights value off another column different from itself (attribute) from the db
            get: fn ($value, $attributes) => $attributes['number_of_days'] - 1,
            // set: fn ($value) => $value,
        );
    }

    // The accessor above can also be written like below in an older sytax
    // public function numberOfNightsAttribute()
    // {
    //     return $this->number_of_days - 1;
    // }


}
