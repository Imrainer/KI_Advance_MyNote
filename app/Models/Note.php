<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $table = "note";

    protected $fillable = [
        'id',  'title', 'content', 'created_by','categories_id','photo','favorite'
    ];

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;


    public function user() {
        return $this->belongsTo(User::class, 'created_by');
    } 

    public function categories() {
        return $this->belongsTo(Categories::class, 'category_id');
    } 
}