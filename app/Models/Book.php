<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Book extends Model {
    public $timestamps = false;
    protected $guarded = [];

    // Relationship 1: A book belongs to a Category
    public function category() {
        return $this->belongsTo(Category::class);
    }

    // Relationship 2: A book belongs to an Author (This was missing!)
    public function author() {
        return $this->belongsTo(Author::class);
    }
}