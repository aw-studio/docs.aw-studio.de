<?php

namespace FjordApp\Config\Crud;

use App\Models\Branch;
use Fjord\Crud\Config\CrudConfig;
use Fjord\Crud\CrudShow;
use FjordApp\Controllers\Crud\BranchController;
use Illuminate\Support\Str;

class BranchConfig extends CrudConfig
{
    /**
     * Model class.
     *
     * @var string
     */
    public $model = Branch::class;

    /**
     * Controller class.
     *
     * @var string
     */
    public $controller = BranchController::class;

    /**
     * Model singular and plural name.
     *
     * @return array
     */
    public function names()
    {
        return [
            'singular' => 'Branch',
            'plural'   => 'Branches',
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
     * Setup show page.
     *
     * @param  \Fjord\Crud\CrudShow $page
     * @return void
     */
    public function show(CrudShow $page)
    {
        $page->card(function ($form) {
            $form->input('name')->title('Name')->width(6)->readonly();
            $form->boolean('active')->title('Active')->width(6);
        });
    }
}
