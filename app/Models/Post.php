<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;  // Add this line
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',        // Title of the post
        'description',  // Description of the post
        'due_date',     // Due date for the post
        'status', 
    ];
}
