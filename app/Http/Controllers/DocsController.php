<?php

namespace App\Http\Controllers;

use App\Actions\UpdateProjectDocs;
use App\Docs\Documentor;
use App\Models\Project;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Symfony\Component\DomCrawler\Crawler;

class DocsController
{
    /**
     * Documentor instance.
     *
     * @var Documentor
     */
    protected $docs;

    /**
     * Create new DocsController instance.
     *
     * @param  Documentor $docs
     * @return void
     */
    public function __construct(Documentor $docs)
    {
        $this->docs = $docs;
    }

    /**
     * Update project docs.
     *
     * @param  string            $vendor
     * @param  string            $projectName
     * @param  string            $secret
     * @param  UpdateProjectDocs $action
     * @return void
     */
    public function update($vendor, $projectName, $secret, UpdateProjectDocs $action)
    {
        if ($secret != env('GITHUB_CI_SECRET')) {
            return;
        }

        $project = Project::where('name', "$vendor/$projectName")->first();

        if (! $project) {
            return;
        }

        $action->execute($project);
    }

    /**
     * Show documentation page.
     *
     * @param  string|null $page
     * @param  string|null $subPage
     * @return View
     */
    public function show($project, $version, $page = null, $subPage = null)
    {
        if (! $page) {
            $page = DEFAULT_PAGE;
        }

        if ($subPage) {
            $page .= "/{$subPage}";
        }

        $project = Project::where('slug', $project)->firstOrFail();

        if (! $version) {
            if (! $branch = $project->branches->where('default', true)->first()) {
                abort(404);
            }

            $version = $branch->name;
        }

        if ($project->path) {
            $version .= "/{$project->path}";
        }

        if (! $this->docs->exists($project->name, $version, $page)) {
            dd(File::files(resource_path('docs/aw-studio/docs/master')));
            dd($project->name, $version, $page);
            abort(404);
        }

        if (! $this->authorize($project, $version, $page)) {
            dd('nooe');
            abort(404);
        }

        $content = $this->docs->get($project->name, $version, $page);

        $title = (new Crawler($content))->filterXPath('//h1');

        $index = $this->docs->getIndex($project->name, $version);

        return view('docs.page', [
            'index'   => $index,
            'title'   => $title,
            'content' => $content,
        ]);
    }

    /**
     * Authorize documentation.
     *
     * @param  string $project
     * @param  string $version
     * @param  string $page
     * @return bool
     */
    protected function authorize($project, $version, $page)
    {
        if (! $project->private) {
            return true;
        }

        if (! auth()->user()) {
            return false;
        }

        foreach ($project->access as $access) {
            if (! $access->active) {
                continue;
            }

            if ($access->username == auth()->user()->nickname) {
                return true;
            }
        }

        return false;
    }
}
