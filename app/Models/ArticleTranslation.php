<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleTranslation extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'text', 'language_code'];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
