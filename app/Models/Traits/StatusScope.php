<?php


namespace App\Models\Traits;


trait StatusScope
{
    public function scopeStatus($query, $status = true)
    {
        $query->where('status', $status);
        return $query;
    }
}
