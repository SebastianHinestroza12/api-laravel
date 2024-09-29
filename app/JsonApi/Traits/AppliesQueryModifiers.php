<?php

namespace App\JsonApi\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

trait AppliesQueryModifiers
{
    /**
     * Apply sparse fields to the query if `fields` parameter is present.
     */
    public function applyFields(Builder $query): Builder|JsonResponse
    {
        $tableColumns = $this->getColumnModel($query);

        if (request()->has('fields')) {
            $fields = request('fields');
            $fieldsArray = array_map('trim', explode(',', $fields));

            if (! in_array('id', $fieldsArray)) {
                $fieldsArray[] = 'id';
            }

            $invalidFields = array_diff($fieldsArray, array_merge($tableColumns, $this->select ?? []));

            if (! empty($invalidFields)) {
                //If there are invalid fields, throw an exception or handle the error
                abort(ResponseAlias::HTTP_BAD_REQUEST, 'Invalid fields: ' . implode(', ', $invalidFields));
            }

            $query->selectFields($fieldsArray);
        } else {
            $query->select($this->select ?? $tableColumns);
        }

        return $query;
    }

    /**
     * Apply filters to the query if `filter` parameter is present.
     */
    public function applyFilters(Builder $query): Builder
    {
        if (request()->has('filter')) {
            $filters = request('filter', []);
            //dd($filters);
            $tableColumns = $this->getColumnModel($query);

            foreach ($filters as $field => $value) {
                if (! in_array($field, $tableColumns)) {
                    abort(ResponseAlias::HTTP_BAD_REQUEST, 'Invalid filter field: ' . htmlspecialchars($field));
                }
            }

            $query->filter($filters);
        }

        return $query;
    }

    /**
     * Apply sorting to the query if `sort` parameter is present.
     */
    public function applySorting(Builder $query): Builder
    {
        if (request()->has('sort')) {
            $sorts = request('sort');
            $tableColumns = $this->getColumnModel($query);
            $sortFields = explode(',', $sorts);

            foreach ($sortFields as $sortField) {
                $cleanSortField = ltrim($sortField, '-');
                if (! in_array($cleanSortField, $tableColumns)) {
                    abort(ResponseAlias::HTTP_BAD_REQUEST, 'Invalid sort field: ' . $sortField);
                }
            }

            $query->sort($sortFields);
        }

        return $query;
    }

    /**
     * Apply pagination to the query if `page` or `per_page` parameters are present.
     */
    public function applyPagination(Builder $query, $perPage = 15, $page = 1): LengthAwarePaginator|Builder
    {
        if (request()->has('page') || request()->has('per_page')) {
            $perPage = request('per_page', 15);
            $page = request('page', 1);

            return $query->paginateCustom($perPage, $page);
        }

        return $query->paginate();
    }

    private function getColumnModel(Builder $query): array
    {
        return Schema::getColumnListing($query->getModel()->getTable());
    }
}
