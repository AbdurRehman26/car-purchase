<?php

namespace App\Data\Repositories;

use Kazmi\Data\Contracts\RepositoryContract;
use Kazmi\Data\Repositories\AbstractRepository;
use App\Data\Models\FileExport;

class FileExportRepository extends AbstractRepository implements RepositoryContract
{
    /**
     *
     * These will hold the instance of FileExport Class.
     *
     * @var object
     * @access public
     *
     **/
    public $model;

    /**
     *
     * This is the prefix of the cache key to which the
     * App\Data\Repositories data will be stored
     * App\Data\Repositories Auto incremented Id will be append to it
     *
     * Example: FileExport-1
     *
     * @var string
     * @access protected
     *
     **/

    protected $_cacheKey = 'FileExport';
    protected $_cacheTotalKey = 'total-FileExport';

    public function __construct(FileExport $model)
    {
        $this->model = $model;
        $this->builder = $model;

    }
}
