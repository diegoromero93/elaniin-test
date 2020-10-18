<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sku',
        'name',
        'qty',
        'amount',
        'description',
        'image'
    ];

    public static $creation_rules = array(
        'sku' => 'required|string|between:8,14|unique:products',
        'name' => 'required|string|between:2,100',
        'qty' => 'required|numeric|min:0|not_in:0',
        'amount' => 'required|numeric|min:0|not_in:0',
        'description' => 'required|min:5',
        'image' => 'required|image:jpeg,png,jpg,gif,svg|max:2048'
    );

    public static $update_rules = array(
        'name' => 'string|between:2,100',
        'qty' => 'numeric|min:0|not_in:0',
        'amount' => 'numeric|min:0|not_in:0',
        'description' => 'min:5',
        'image' => 'image:jpeg,png,jpg,gif,svg|max:2048'
    );

}
