<?php

namespace FjordApp\Config\User;

use Fjord\Crud\Config\CrudConfig;
use Fjord\Crud\CrudIndex;
use Fjord\Crud\CrudShow;
use Fjord\Page\Table\ColumnBuilder;
use Fjord\User\Models\FjordUser;
use FjordApp\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

class UserConfig extends CrudConfig
{
    /**
     * Model class.
     *
     * @var string
     */
    public $model = FjordUser::class;

    /**
     * Controller class.
     *
     * @var string
     */
    public $controller = UserController::class;

    /**
     * Route prefix.
     *
     * @return string
     */
    public function routePrefix()
    {
        return 'fjord-users';
    }

    /**
     * Crud singular & plural name.
     *
     * @return array
     */
    public function names()
    {
        return [
            'singular' => ucfirst(__f('base.user')),
            'plural'   => ucfirst(__f('base.users')),
        ];
    }

    /**
     * Build user index table.
     *
     * @param CrudIndex $page
     *
     * @return void
     */
    public function index(CrudIndex $page)
    {
        $page->table(fn ($table) => $this->indexTable($table))
            ->query(fn ($query) => $query->with('ordered_roles'))
            ->sortByDefault('id.desc')
            ->search('username', 'first_name', 'last_name', 'email');
    }

    /**
     * User index table.
     *
     * @param  ColumnBuilder $table
     * @return void
     */
    public function indexTable(ColumnBuilder $table)
    {
        $table->col()
            ->value('{first_name} {last_name}')
            ->label('Name');

        $table->col()
            ->value('email')
            ->label('E-Mail');

        $table->component('fj-permissions-fjord-users-roles')
            ->link(false)
            ->label(ucfirst(__f('base.roles')));

        $table->component('fj-permissions-fjord-users-apply-role')
            ->authorize(fn ($user) => $user->can('update fjord-user-roles'))
            ->label('')
            ->link(false)
            ->small();
    }

    /**
     * Crud show container.
     *
     * @param CrudShow $page
     *
     * @return void
     */
    public function show(CrudShow $page)
    {
        $page->card(function ($form) {
            $form->input('first_name')
                ->width(1 / 2)
                ->creationRules('required')
                ->rules('min:2')
                ->title(ucwords(__f('base.first_name')));

            $form->input('last_name')
                ->width(1 / 2)
                ->creationRules('required')
                ->rules('min:2')
                ->title(ucwords(__f('base.last_name')));

            $form->input('email')
                ->width(1 / 2)
                ->creationRules('required')
                ->rules('email:rfc,dns', 'unique:fjord_users,email')
                ->title('E-Mail');

            $form->input('username')
                ->width(1 / 2)
                ->creationRules('required')
                ->rules('min:2', 'max:60', 'unique:fjord_users,username')
                ->title(ucwords(__f('base.username')));

            $form->password('password')
                ->title(ucwords(__f('base.password')))
                ->width(1 / 2);
        });
    }
}
