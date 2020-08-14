<?php

namespace App\Http\Controllers;

use App\Docs\Documentor;
use App\Models\Project;
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
            $version = $project->branches->where('default', true)->first()->branch;
        }

        if ($project->path) {
            $version .= "/{$project->path}";
        }

        if (! $this->docs->exists($project->name, $version, $page)) {
            abort(404);
        }

        if (! $this->authorize($project, $version, $page)) {
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
