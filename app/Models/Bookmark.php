<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Bookmark extends Model
{
    //
    protected $table = 'bookmarks';
    protected $fillable = ['url','title','user_id','description','notes','type','image'];
}