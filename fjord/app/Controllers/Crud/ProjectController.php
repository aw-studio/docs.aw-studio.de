<?php

namespace FjordApp\Controllers\Crud;

use Fjord\Crud\Controllers\CrudController;
use Fjord\User\Models\FjordUser;
use GuzzleHttp;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Database\Eloquent\Builder;

class ProjectController extends CrudController
{
    /**
     * Crud model class name.
     *
     * @var string
     */
    protected $model = \App\Models\Project::class;

    /**
     * Fill model on create.
     *
     * @param  Project $model
     * @return void
     */
    public function fillOnStore($model)
    {
        $url = request()->payload['url'];
        if (! $model->title) {
            $model->title = ucfirst(last(explode('/', $url)));
        }
        $model->name = explode('com/', $url)[1];

        $model->private = false;

        try {
            (new GuzzleHttp\Client)->get($url);
        } catch (RequestException $e) {
            if ($e->getResponse()->getStatusCode() == 404) {
                $model->private = true;
            } else {
                throw $e;
            }
        }
    }

    /**
     * Authorize request for authenticated fjord-user and permission operation.
     * Operations: create, read, update, delete.
     *
     * @param  FjordUser $user
     * @param  string    $operation
     * @param  int       $id
     * @return bool
     */
    public function authorize(FjordUser $user, string $operation, $id = null): bool
    {
        return true;
    }

    /**
     * Initial query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(): Builder
    {
        return $this->model::query();
    }
}
