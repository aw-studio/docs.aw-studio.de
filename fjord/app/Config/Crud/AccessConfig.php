<?php

namespace FjordApp\Config\Crud;

use App\Models\Access;
use Fjord\Crud\Config\CrudConfig;
use Fjord\Crud\CrudIndex;
use Fjord\Crud\CrudShow;
use FjordApp\Controllers\Crud\AccessController;
use Illuminate\Support\Str;

class AccessConfig extends CrudConfig
{
    /**
     * Model class.
     *
     * @var string
     */
    public $model = Access::class;

    /**
     * Controller class.
     *
     * @var string
     */
    public $controller = AccessController::class;

    /**
     * Model singular and plural name.
     *
     * @return array
     */
    public function names()
    {
        return [
            'singular' => 'Access',
            'plural'   => 'Access',
        ];
    }

    /**
     * Get crud route prefix.
     *
     * @return string
     */
    public function routePrefix()
    {
        return Str::slug((new $this->model)->getTable());
    }

    /**
     * Build index page.
     *
     * @param  Fjord\Crud\CrudIndex $page
     * @return void
     */
    public function index(CrudIndex $page)
    {
        // Expand html container to full width.
        $page->expand(false);

        $page->table(function ($table) {
            $table->col('Nickname')->value('{username}')->sortBy('username');
            $table->col('Repository')->value('{repo}')->sortBy('repo');
        })
            ->sortByDefault('id.desc')
            ->search('title')
            ->sortBy([
                'id.desc' => __f('fj.sort_new_to_old'),
                'id.asc'  => __f('fj.sort_old_to_new'),
            ])
            ->width(12);
    }

    /**
     * Setup show page.
     *
     * @param  \Fjord\Crud\CrudShow $page
     * @return void
     */
    public function show(CrudShow $page)
    {
        $page->card(function ($form) {
            $form->input('username')->title('Username')->width(6);
            $form->input('repo')->title('Repository')->width(6);
            $form->boolean('active')->title('Active')->width(6);
        });
    }
}
