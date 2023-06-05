<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin Builder
 */
class ArticleTranslation extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'text', 'language_code'];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}
