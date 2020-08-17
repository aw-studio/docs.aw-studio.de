<?php

namespace FjordApp\Actions\Project;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class UpdateProjectDocsAction
{
    /**
     * Run the action.
     *
     * @param  Collection   $models
     * @return JsonResponse
     */
    public function run(Collection $models)
    {
        $branches = $models->first()->branches()->where('active', true)->get();

        foreach ($branches as $branch) {
            $models->first()->pullOrClone($branch->name);
        }

        $count = $branches->count();

        return response()->success("Updated [{$count}] versions.");
    }
}
