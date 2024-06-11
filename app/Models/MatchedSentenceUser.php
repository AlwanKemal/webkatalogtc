<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchedSentenceUser extends Model
{
    use HasFactory;

    protected $table = 'matched_sentences_user';

    protected $fillable = [
        'sentence',
        'translated_sentence',
        'user_id',
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
