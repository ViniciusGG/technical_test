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
        $record->update($data);
        return $record;
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
