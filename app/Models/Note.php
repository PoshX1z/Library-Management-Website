<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Note extends Model {
    public $timestamps = false;
    protected $guarded = [];

    public function staff() {
        return $this->belongsTo(Staff::class);
    }
}