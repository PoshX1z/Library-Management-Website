<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Borrow extends Model {
    public $timestamps = false;
    protected $guarded = [];

    // Relationship: Borrow connects to a Member and a Book
    public function member() {
        return $this->belongsTo(Member::class);
    }

    public function book() {
        return $this->belongsTo(Book::class);
    }
}