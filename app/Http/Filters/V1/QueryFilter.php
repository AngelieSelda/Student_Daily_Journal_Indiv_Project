<?php

namespace App\Http\Filters\V1;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class QueryFilter
{
    protected Builder $builder;
    protected Request $request;

    protected array $sortable = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach ($this->request->all() as $key => $value) {
            if ($key !== 'apply' && !str_starts_with($key, '__') && method_exists($this, $key)) {
                $this->$key($value);
            }
        }

        return $builder;
    }

    protected function sort($value): void
    {
        $sortAttributes = explode(',', $value);

        foreach ($sortAttributes as $sortAttribute) {
            $direction = 'asc';

            // Kapag may kasamang minus sa unahan, ibig sabihin ay descending order
            if (str_starts_with($sortAttribute, '-')) {
                $direction = 'desc';
                $sortAttribute = substr($sortAttribute, 1);
            }

            // Kung wala sa whitelist o sortable array, laktawan
            if (!in_array($sortAttribute, $this->sortable, true)
                && !array_key_exists($sortAttribute, $this->sortable)) {
                continue;
            }

            // I-translate ang public name patungo sa totoong database column name kung kinakailangan
            $columnName = $this->sortable[$sortAttribute] ?? $sortAttribute;

            $this->builder->orderBy($columnName, $direction);
        }
    }
}
