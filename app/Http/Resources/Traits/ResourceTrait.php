<?php

namespace App\Http\Resources\Traits;

use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorInterface;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

trait ResourceTrait
{
    protected ?string $title;

    protected ?string $description;

    protected string $type;

    protected array $meta = [];

    /**
     * ApiResourceTrait constructor.
     *
     * @param mixed       $resource
     * @param string|null $title
     * @param string|null $description
     * @param string      $type
     */
    public function __construct($resource, string $title = null, string $description = null, string $type = 'success')
    {
        $this->title = $title;
        $this->description = $description;
        $this->type = $type;

        if ($resource instanceof LengthAwarePaginator || $resource instanceof LengthAwarePaginatorInterface) {
            $this->meta = [
                'total'        => $resource->total(), // Total records
                'per_page'     => $resource->perPage(), // Records per page
                'current_page' => $resource->currentPage(),
                'last_page'    => $resource->lastPage(),
            ];

            $resource = $resource->getCollection();
        }

        parent::__construct($resource);
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param Request $request
     * @return array
     */
    public function with($request): array
    {
        $data = [];

        if (!empty($this->meta)) {
            $data['meta'] = $this->meta;
        }

        $data['message'] = [
            'title'       => $this->title,
            'description' => $this->description,
            'type'        => $this->type,
        ];

        $data['errors'] = [];

        return $data;
    }
}
