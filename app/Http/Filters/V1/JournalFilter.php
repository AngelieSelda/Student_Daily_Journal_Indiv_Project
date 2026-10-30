<?php

namespace App\Http\Filters\V1;

class JournalFilter extends QueryFilter
{
    public function createdAt($value)
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('created_at', $dates);
        }

        return $this->builder->whereDate('created_at', $value);
    }

    public function updatedAt($value)
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('updated_at', $dates);
        }

        return $this->builder->whereDate('updated_at', $value);
    }

    public function include($value)
    {
        $allowed = array_intersect(explode(',', $value), ['author']);

        return $this->builder->with($allowed);
    }

    public function status($value)
    {
        return $this->builder->whereIn('status', explode(',', $value));
    }

    public function title($value)
    {
        $likeStr = str_replace('*', '%', $value);

        return $this->builder->where('title', 'like', $likeStr);
    }
}