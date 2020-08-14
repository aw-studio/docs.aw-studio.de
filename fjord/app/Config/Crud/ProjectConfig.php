<?php

namespace FjordApp\Config\Crud;

use App\Models\Project;
use Fjord\Crud\Config\CrudConfig;
use Fjord\Crud\CrudIndex;
use Fjord\Crud\CrudShow;
use Fjord\Support\Bootstrap;
use FjordApp\Actions\Project\LoadProjectBranchesAction;
use FjordApp\Actions\Project\UpdateProjectDocsAction;
use FjordApp\Controllers\Crud\ProjectController;
use Illuminate\Support\Str;

class ProjectConfig extends CrudConfig
{
    /**
     * Model class.
     *
     * @var string
     */
    public $model = Project::class;

    /**
     * Controller class.
     *
     * @var string
     */
    public $controller = ProjectController::class;

    /**
     * Model singular and plural name.
     *
     * @return array
     */
    public function names()
    {
        return [
            'singular' => 'Project',
            'plural'   => 'Projects',
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
            $table->col('name')->value('{name}')->sortBy('name');
            $table->col('slug')->value('{slug}')->sortBy('slug');
            $table->col('Access')->value('private', [
                false => Bootstrap::badge('public', 'success'),
                true  => Bootstrap::badge('private', 'info'),
            ])->small();
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
        $page->headerRight()->action('Load Branches', LoadProjectBranchesAction::class)->variant('outline-primary');
        $page->headerRight()->action('Update Docs', UpdateProjectDocsAction::class)->variant('outline-primary');

        $page->card(function ($form) {
            $form->input('url')->title(fa('fab', 'github').' Github Url')
                ->placeholder('https://www.github.com/...')->width(6)->rules(function ($attribute, $value, $fail) {
                    if (! $this->validateGithubUrl($value)) {
                        $fail('Must be a url to a github repository.');
                    }
                });
            $form->input('title')->title('title')->width(6);
            $form->input('name')->title('Name')->width(6)->readonly();
            $form->input('token')->title('Token')->width(6);
            $form->input('path')->title('Path')->hint('Documentation path.')->width(6);
            $form->boolean('private')->title('Private')->width(6);
        });

        $page->card(function ($form) {
            $form->relation('branches')->title('Branches')->preview(function ($preview) {
                $preview->col('title')->value('{title}');
                $preview->col('Name')->value('{name}');
                $preview->col('active')->value('active', [
                    true  => Bootstrap::badge('active', 'success'),
                    false => Bootstrap::badge('hidden', 'secondary'),
                ])->small();
                $preview->col('state')->value('default', [
                    true => Bootstrap::badge('default', 'primary'),
                ])->small();
            })->readonly();
        });
    }

    /**
     * Validate github url.
     *
     * @param  string $value
     * @return bool
     */
    protected function validateGithubUrl($value)
    {
        $parsed = parse_url($value);

        if (! array_key_exists('host', $parsed)) {
            return false;
        }

        if ($parsed['host'] != 'github.com') {
            return false;
        }

        return count(explode('/', $parsed['path'])) === 3;
    }
}
