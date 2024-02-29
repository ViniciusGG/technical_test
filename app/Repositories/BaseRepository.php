<?php

namespace App\Repositories;

class BaseRepository
{
    protected $model;
    protected $take = 10;

    public function __construct($model)
    {
        $this->model = new $model;
    }

    public function exists($id)
    {
        return $this->model->findOrFail($id);
    }

    public function getAll()
    {
        return $this->model->get();
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function update($id, $data)
    {
        $record = $this->getById($id);
        return $record->update($data);
    }

    public function delete($id)
    {
        $record = $this->getById($id);
        $record->delete();
        return $record;
    }

    public function getById($id, $relationships = [])
    {
        if ($relationships) {
            return $this->model->with($relationships)->findOrFail($id);
        }
        return $this->model->findOrFail($id);
    }

    public function getCountryByCode($code, $relationships = [])
    {
        if ($relationships) {
            return $this->model->where('code', $code)->with($relationships)->get();
        }
        return $this->model->where('code', $code)->get();
    }


    public function getWithPaginate($limit = 0, $offset = 0)
    {
        return $this->model->offset((int)$offset)->limit((int)$limit)->get();
    }

    public function get($take = 0, $columns = ["*"], $relationships = [])
    {
        if ($take === 0) {
            $take = $this->take;
        }

        if ($relationships) {
            return $this->model->with($relationships)->paginate($take, $columns);
        }
        return $this->model->paginate($take, $columns);
    }

    public function filter($filters)
    {
        $query = $this->model->newQuery();
        $columns = $filters['columns'] ?? ['*'];
        $take = $filters['take'] ?? $this->take;
        $active = $filters['active'] ?? '';

        if($active == 1 || $active == 0){
            $query->orWhere('active', $active);
        }

        if (isset($filters['search'])) {
            $search = $filters['search'];

            $query->where(function ($query) use ($search) {
                $searchFields = empty($this->model->searchFields) ? ['name'] : $this->model->searchFields;
                foreach ($searchFields as $searchField) {
                    $query->orWhere($searchField, 'like', '%' . $search . '%');
                }
            });
        }

        if (isset($filters['sortBy'])) {
            $sortDirection = $filters['sortDirection'] ?? 'ASC';
            $query->orderBy($filters['sortBy'], $sortDirection);
        }

        return $query->paginate($take, $columns);
    }
}
