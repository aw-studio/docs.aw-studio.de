<?php

namespace App\Http\Controllers;

use App\Fjuse;
use App\Docs\Documentor;
use Illuminate\View\View;
use App\Http\Requests\DocsRequest;
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
    public function show(DocsRequest $request, $page = null, $subPage = null)
    {
        if (! $page) {
            $page = DEFAULT_PAGE;
        }

        if ($subPage) {
            $page .= "/{$subPage}";
        }

        $content = $this->docs->get($page);
        $title = (new Crawler($content))->filterXPath('//h1');

        return view('docs.page', [
            'index'          => $this->docs->getIndex('readme'),
            'title'          => '$title',
            'content'        => $content,
        ]);
    }
}
