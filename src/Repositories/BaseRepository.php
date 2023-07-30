<?php

namespace RafCom\Repositories;

use Illuminate\Database\Eloquent\{Builder, Collection, Model};
use Illuminate\Pagination\LengthAwarePaginator;

class BaseRepository
{
    /**
     * Current page of the repository
     *
     * @var int
     */
    public int $currentPage = 1;

    /**
     * Pagination size for per page
     *
     * @var int
     */
    public int $perPage = 10;

    /**
     * Model for current repository
     *
     * @var ?mixed
     */
    protected mixed $model = null;

    /**
     * Query instance container
     *
     * @var ?Builder
     */
    public $query;

    /**
     * Collection container of the repository
     *
     * @var Collection|LengthAwarePaginator
     */
    protected LengthAwarePaginator|Collection $collection;

    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->perPage = request()->input(
            'per_page',
            config('app.page_limit', 10)
        );
        $this->currentPage = request()->input('page', 1);
    }

    /**
     * Call uncreated method in the class as query builder query
     *
     * @param  string  $method
     * @param  array   $args
     * @return mixed
     */
    public function __call(string $method, array $args)
    {
        // Default we call this class instance first
        $call = [$this, $method];

        if (is_null($this->query)) {
            $this->getQuery();
        }

        // If method not exist in this class then wrap model with query builder
        // So that queryBuilder's method is being called instead
        if (!in_array($method, get_class_methods(self::class))) {
            $call = match (true) {
                in_array($method, get_class_methods(get_class($this->query))) => [$this->query, $method],
                default => [$this->model, $method],
            };
        }

        $returned = call_user_func_array($call, $args);

        // If returned instance is instanceof Builder then assign to it
        if ($returned instanceof Builder) {
            $this->setQuery($returned);

            return $this;
        }

        return $returned;
    }

    /**
     * Set current page of query
     *
     * @param  int  $currentPage
     * @return $this
     */
    public function setCurrentPage(int $currentPage = 1): static
    {
        $this->currentPage = $currentPage;

        return $this;
    }

    /**
     * Set pagination size of the pagination result
     *
     * @param  int $pageSize
     * @return $this
     */
    public function setPaginationSize(int $pageSize = 10): static
    {
        $this->perPage = $pageSize;

        return $this;
    }

    /**
     * Set model of the repository
     *
     * @param Model $model
     * @return $this
     */
    public function setModel(Model $model): static
    {
        $this->model = $model;

        // The following breaks CLI. Don't do this.  Put it elsewhere.
        //$this->query = $model->newQuery();

        return $this;
    }

    /**
     * Set query of the repository
     *
     * @param Builder|BaseRepository $query
     * @return $this
     */
    public function setQuery(Builder|BaseRepository $query): static
    {
        if ($query instanceof $this) {
            $query = $this->getQuery();
        }

        $this->query = $query;

        return $this;
    }

    /**
     * Get query of the repository
     *
     * @return Builder
     */
    public function getQuery(): Builder
    {
        return $this->query = $this->query ?: $this->getModel()->query();
    }

    /**
     * Get model of the repository
     *
     * @return mixed
     */
    public function getModel(): mixed
    {
        return $this->model;
    }

    /**
     * Set collection of the repository
     *
     * @param Collection $collection
     * @return $this
     */
    public function setCollection(Collection $collection): static
    {
        $this->collection = $collection;

        return $this;
    }

    /**
     * Execute eloquent get() method
     *
     * @return Builder[]|Collection
     */
    public function get()
    {
        return $this->collection = $this->query->get();
    }

    /**
     * Execute eloquent save() method
     *
     * @param  array  $data
     * @return mixed
     */
    public function save(array $data): mixed
    {
        $model = $this->getModel();
        $model->fill($data);
        $model->save();

        return $model;
    }

    /**
     * Execute eloquent create() method
     *
     * @param  array  $data
     * @return mixed
     */
    public function create(array $data): mixed
    {
        $model = $this->getModel();
        return $model->create($data);
    }

    /**
     * Update instance whether it's model or query builder
     *
     * @param  array  $data
     * @return bool
     */
    public function update(array $data): bool
    {
        $query = ($this->query !== $this->model) ?
            $this->getQuery() :
            $this->getModel();

        return $query->update($data);
    }

    /**
     * Execute eloquent paginate() methid
     *
     * @param  int  $perPage
     * @param  int  $page
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 0, int $page = 0): LengthAwarePaginator
    {
        $perPage = $perPage ?: $this->perPage;
        $page = $page ?: $this->currentPage;

        return $this->collection = $this->query->paginate(
            $perPage,
            ['*'],
            'page',
            $page
        );
    }

    /**
     * Paginate result as group of collection.
     *
     * @param string $groupBy
     * @param string|null $condition
     * @param int $perPage
     * @param int $page
     * @return LengthAwarePaginator
     */
    public function paginateAsGroup(
        string $groupBy,
        string $condition = null,
        int $perPage = 0,
        int $page = 0
    ): LengthAwarePaginator {
        $perPage = $perPage ?: $this->perPage;
        $page = $page ?: $this->currentPage;

        // Get pagination of keys
        $keysPagination = $this->getQuery()
            ->select($groupBy)
            ->groupBy($groupBy);

        if ($condition) $keysPagination = $keysPagination->where($groupBy, $condition);

        $keysPagination = $keysPagination->paginate($perPage, ['*'], 'page', $page);

        // Grouping keys. This will be the holder/key to sub-collections
        $keys = $keysPagination->getCollection()
            ->pluck($groupBy)
            ->toArray();

        // Get the collection that will be shown
        $model = $this->getModel();
        $collection = $model->whereIn($groupBy, $keys)
            ->get()
            ->groupBy($groupBy);

        $keysPagination->setCollection($collection);

        return $keysPagination;
    }
}
