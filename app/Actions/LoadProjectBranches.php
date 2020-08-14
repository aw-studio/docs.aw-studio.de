<?php

namespace App\Actions;

use App\Docs\Scraper;
use App\Models\ProjectBranch;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class LoadProjectBranches
{
    /**
     * Scraper instance.
     *
     * @var Scraper
     */
    protected $scraper;

    /**
     * Create new LoadProjectBranches instance.
     *
     * @param Scraper $scraper
     */
    public function __construct(Scraper $scraper)
    {
        $this->scraper = $scraper;
    }

    /**
     * Run the action.
     *
     * @param  Collection   $models
     * @return JsonResponse
     */
    public function execute($model)
    {
        $branches = $this->scraper->branches($model);

        foreach ($branches as $branch) {
            ProjectBranch::firstOrCreate([
                'branch'     => $branch['name'],
                'project_id' => $model->id,
            ], [
                'title' => ucfirst($branch['name']),
            ]);
        }

        $this->getDefaultBranch($model);

        return $branches;
    }

    /**
     * Set default branch.
     *
     * @param  string $model
     * @return void
     */
    protected function getDefaultBranch($model)
    {
        $repo = $this->scraper->repo($model);

        ProjectBranch::where('project_id', $model->id)->where('branch', $repo['default_branch'])->update(['default' => true]);
    }
}
