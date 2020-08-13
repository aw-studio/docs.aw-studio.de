<?php

namespace FjordApp\Config\Crud;

use App\Models\User;
use Fjord\Contracts\Page\ColumnBuilder;
use Fjord\Crud\Config\CrudConfig;
use Fjord\Crud\CrudIndex;
use Fjord\Crud\CrudShow;
use FjordApp\Controllers\Crud\UserController;
use Illuminate\Support\Str;

class UserConfig extends CrudConfig
{
    /**
     * Model class.
     *
     * @var string
     */
    public $model = User::class;

    /**
     * Controller class.
     *
     * @var string
     */
    public $controller = UserController::class;

    /**
     * Model singular and plural name.
     *
     * @return array
     */
    public function names()
    {
        return [
            'singular' => 'User',
            'plural'   => 'Users',
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

        $page->table(fn (ColumnBuilder $table) => $this->buildTable($table))
            ->sortByDefault('id.desc')
            ->search('nickname', 'name', 'email')
            ->sortBy([
                'id.desc' => __f('fj.sort_new_to_old'),
                'id.asc'  => __f('fj.sort_old_to_new'),
            ])
            ->width(12);
    }

    /**
     * Build index table columns.
     *
     * @param  ColumnBuilder $table
     * @return void
     */
    protected function buildTable(ColumnBuilder $table)
    {
        $table->image('Image')->src('avatar_url')->sortBy('avatar_url')->small()->circle();
        $table->col('Nick')->value('{nickname}')->sortBy('nickname')->small();
        $table->col('Name')->value('{name}')->sortBy('name');
        $table->col('Email')->value('{email}')->sortBy('email');
        $table->col('Service')->value('service', [
            'github' => fa('fab', 'github').' GiHub',
            'gitlab' => fa('fab', 'gitlab').' GitLab',
        ])->small()->center()->sortBy('service')->link('{profile_url}');
    }

    /**
     * Setup show page.
     *
     * @param  \Fjord\Crud\CrudShow $page
     * @return void
     */
    public function show(CrudShow $page)
    {
        $page->view('admin::user.avatar')->width(2);

        $page->card(function ($form) {
            $form->input('nickname')->title('Nickname')->readonly()->width(6);
            $form->input('email')->title('Email')->readonly()->width(6);
        })->width(8);

        $page->card(function ($form) {
            $form->boolean('docs_access')->title('Docs Zugriff')->width(12);
        })->width(2);
    }
}
