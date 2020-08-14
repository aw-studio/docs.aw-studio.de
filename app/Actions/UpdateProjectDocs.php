<?php

namespace App\Actions;

use App\Docs\Documentor;
use App\Docs\Scraper;
use App\Models\ProjectBranch;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class UpdateProjectDocs
{
    /**
     * Scraper instance.
     *
     * @var Scraper
     */
    protected $scraper;

    /**
     * Documentor instance.
     *
     * @var Documentor
     */
    protected $docs;

    /**
     * Create new LoadProjectBranches instance.
     *
     * @param Scraper    $scraper
     * @param Documentor $docs
     */
    public function __construct(Scraper $scraper, Documentor $docs)
    {
        $this->scraper = $scraper;
        $this->docs = $docs;
    }

    /**
     * Run the action.
     *
     * @param  Collection   $models
     * @return JsonResponse
     */
    public function execute($model)
    {
        foreach ($model->branches as $branch) {
            $this->pullOrClone($branch);
        }
    }

    protected function pullOrClone($branch)
    {
        if ($this->docs->exists($branch->project->name, $branch->branch)) {
            $this->pull($branch);
        } else {
            $this->clone($branch);
        }
    }

    /**
     * Pull project.
     *
     * @param  ProjectBranch $branch
     * @return void
     */
    protected function pull($branch)
    {
        $path = $this->getPath($branch);

        exec("cd {$path} && git pull", $output);
    }

    /**
     * Clone project.
     *
     * @param  ProjectBranch $branch
     * @return void
     */
    protected function clone($branch)
    {
        $path = $this->getPath($branch);

        exec("git clone -b {$branch->branch} {$branch->project->clone_url} {$path}", $output);
    }

    /**
     * Get docs path.
     *
     * @param  ProjectBranch $branch
     * @return string
     */
    protected function getPath($branch)
    {
        $path = "resources/docs/{$branch->project->name}/{$branch->branch}";
        if (! app()->runningInConsole()) {
            $path = '../'.$path;
        }

        return $path;
    }
}