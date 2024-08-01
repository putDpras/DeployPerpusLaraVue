<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Books extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'books';
    protected $fillable = ['title','summary','image','stok','category_id'];

    public function categories(){
        return $this->belongsTo(Categories::class, 'category_id');
    }

    // public function listBorrows(){
    //     return $this->belongsToMany(User::class, 'borrows', 'book_id', 'user_id');
    // }
    public function listBorrows(){
        return $this->hasMany(Borrows::class, 'book_id');
    }
    
}
