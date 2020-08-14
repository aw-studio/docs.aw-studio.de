<?php

namespace FjordApp\Actions\Project;

use App\Actions\LoadProjectBranches;
use Github\Exception\RuntimeException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class LoadProjectBranchesAction
{
    /**
     * Run the action.
     *
     * @param  Collection   $models
     * @return JsonResponse
     */
    public function run(Collection $models, LoadProjectBranches $action)
    {
        try {
            $branches = $action->execute($models->first());
        } catch (RuntimeException $e) {
            return response()->danger('Couldn\'t get branches for '.$models->first()->name);
        }

        $count = count($branches);

        return response()->success("Found [{$count}] branches.");
    }
}
