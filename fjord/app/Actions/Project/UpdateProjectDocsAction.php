<?php

namespace FjordApp\Actions\Project;

use App\Actions\UpdateProjectDocs;
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
    public function run(Collection $models, UpdateProjectDocs $action)
    {
        $action->execute($models->first());

        $count = $models->first()->branches->count();

        return response()->success("Updated [{$count}] versions.");
    }
}
