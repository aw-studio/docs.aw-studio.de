<?php

namespace App\Actions;

use App\Docs\Documentor;
use App\Docs\Scraper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

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
        if ($this->docs->exists($branch->project->name, $branch->name)) {
            $this->pull($branch);
        } else {
            $this->clone($branch);
        }
    }

    /**
     * Pull project.
     *
     * @param  Branch $branch
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
     * @param  Branch $branch
     * @return void
     */
    protected function clone($branch)
    {
        $path = $this->getPath($branch);

        if (! $branch->project->path) {
            exec("git clone -b {$branch->name} {$branch->project->clone_url} {$path}", $output);

            return;
        }

        File::ensureDirectoryExists($path);
        exec('
            cd '.$path.' \
            && git init \
            && git remote add -f origin '.$branch->project->clone_url.' \
            && git config core.sparseCheckout true \
            && echo "/'.$branch->project->path.'" >> .git/info/sparse-checkout \
            && git checkout '.$branch->name.' \
            && git pull origin '.$branch->name.'
        ');
    }

    /**
     * Get docs path.
     *
     * @param  Branch $branch
     * @return string
     */
    protected function getPath($branch)
    {
        $path = "resources/docs/{$branch->project->name}/{$branch->name}";

        if (! app()->runningInConsole()) {
            $path = '../'.$path;
        }

        return $path;
    }
}
