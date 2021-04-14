<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaperSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'paper_id',
        'title',
        'item_score',
        'item_count',
        'status',
        'index',
    ];

    public function paper()
    {
        return $this->belongsTo(Paper::class, 'paper_id');
    }
}
