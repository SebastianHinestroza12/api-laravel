<?php

namespace App\JsonApi\Mixins;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class JsonApiQueryBuilder
{
    public function filter(): Closure
    {
        return function ($filters) {
            /** @var Builder $this */
            return $this->when($filters, function ($query, $filters) {
                foreach ($filters as $field => $value) {
                    if (is_array($value)) {
                        foreach ($value as $operator => $val) {
                            switch ($operator) {
                                case 'gt':
                                    $query->where($field, '>', $val);
                                    break;
                                case 'lt':
                                    $query->where($field, '<', $val);
                                    break;
                                case 'gte':
                                    $query->where($field, '>=', $val);
                                    break;
                                case 'lte':
                                    $query->where($field, '<=', $val);
                                    break;
                                case 'neq':
                                    $query->where($field, '!=', $val);
                                    break;
                                case 'in':
                                    $query->whereIn($field, $val);
                                    break;
                                case 'not_in':
                                    $query->whereNotIn($field, $val);
                                    break;
                                case 'like':
                                    $query->where($field, 'LIKE', $val);
                                    break;
                                case 'not_like':
                                    $query->where($field, 'NOT LIKE', $val);
                                    break;
                                case 'is_null':
                                    $query->whereNull($field);
                                    break;
                                case 'is_not_null':
                                    $query->whereNotNull($field);
                                    break;
                                case 'starts_with':
                                    $query->where($field, 'LIKE', "{$val}%");
                                    break;
                                case 'ends_with':
                                    $query->where($field, 'LIKE', "%{$val}");
                                    break;
                                case 'between':
                                    $query->whereBetween($field, $val);
                                    break;
                                case 'date':
                                    $query->whereDate($field, $val);
                                    break;
                                case 'year':
                                    $query->whereYear($field, $val);
                                    break;
                                case 'month':
                                    $query->whereMonth($field, $val);
                                    break;
                                case 'day':
                                    $query->whereDay($field, $val);
                                    break;
                                default:
                                    $query->where($field, 'LIKE', "%{$val}%");
                                    break;
                            }
                        }
                    } else {
                        $query->where($field, 'LIKE', "%{$value}%");
                    }
                }
            });
        };
    }

    public function sort(): Closure
    {
        return function ($sorts) {
            /** @var Builder $this */
            if (! $sorts) {
                return $this;
            }

            foreach ($sorts as $sortField) {
                $direction = Str::startsWith($sortField, '-') ? 'desc' : 'asc';
                $field = ltrim($sortField, '-');
                $this->orderBy($field, $direction);
            }

            return $this;
        };
    }

    public function paginateCustom(): Closure
    {
        return function ($perPage = 15, $page = 1, $columns = ['*']) {
            /** @var Builder $this */
            return $this->paginate($perPage, $columns, 'page', $page);
        };
    }

    public function selectFields(): Closure
    {
        return function ($fields) {
            /** @var Builder $this */
            if ($fields) {
                $this->select($fields);
            }

            return $this;
        };
    }
}
