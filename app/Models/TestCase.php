<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestCase extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_domain',
        'module_name',
        'test_description',
        'test_case_type',
        'test_step',
        'test_data',
        'expected_result',
        'actual_result',
    ];   
}
