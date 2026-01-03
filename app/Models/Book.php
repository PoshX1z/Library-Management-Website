<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Book extends Model {
    public $timestamps = false; // We use 'added_at' in SQL, not standard laravel timestamps
    protected $guarded = []; // Allow all fields to be filled

    // Relationship: A book belongs to a category
    public function category() {
        return $this->belongsTo(Category::class);
    }
}