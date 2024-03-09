<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelectedTestCase extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_case_id',
        'user_id',
    ];

    public function testCase()
    {
        return $this->belongsTo(TestCase::class, 'test_case_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
