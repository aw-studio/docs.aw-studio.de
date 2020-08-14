<?php

namespace App\Docs;

use App\Models\Project;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use ParsedownExtra;

class Documentor
{
    /**
     * Filesystem instance.
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * Cache isntance.
     *
     * @var Cache
     */
    protected $cache;

    /**
     * Parser instance.
     *
     * @var Parser
     */
    protected $parser;

    /**
     * Create new Documentor instance.
     *
     * @param Filesystem $files
     * @param Cache      $cache
     * @param Parser     $parser
     */
    public function __construct(Filesystem $files, Cache $cache, Parser $parser)
    {
        $this->files = $files;
        $this->cache = $cache;
        $this->parser = $parser;
    }

    /**
     * Dermines if a page exists.
     *
     * @param  string      $project
     * @param  string      $version
     * @param  string|null $page
     * @return bool
     */
    public function exists($project, $version, $page = null)
    {
        return $this->files->exists(
            $this->path($project, $version, $page)
        );
    }

    /**
     * Get index.
     *
     * @param  string $project
     * @param  string $version
     * @return string
     */
    public function getIndex($project, $version)
    {
        return $this->cache->remember("index.docs.{$project}.{$version}", 5, function () use ($project, $version) {
            $content = Str::after((new ParsedownExtra)->text(
                $this->files->get($this->path($project, $version, 'readme'))
            ), '<h2>Index</h2>');

            preg_match_all('/(?<=\bhref=")[^"]*/', $content, $matches);

            foreach ($matches[0] as $match) {
                $page = str_replace('.md', '', $match);

                $route = route('docs', [
                    'project' => Project::where('name', $project)->first()->slug,
                    'version' => $version,
                    'page'    => trim($page, '/'),
                ]);

                $content = str_replace($match, $route, $content);
            }

            return $content;
        });
    }

    /**
     * Get docs for the given project page and the corresponding version.
     *
     * @param  string $project
     * @param  string $version
     * @param  string $page
     * @return string
     */
    public function get($project, $version, $page)
    {
        return $this->cache->remember("docs.{$project}.{$version}.{$page}", 5, function () use ($project, $version, $page) {
            if (! $this->exists($project, $version, $page)) {
                return;
            }

            $content = $this->parser->text(
                $this->files->get($this->path($project, $version, $page))
            );

            return $content;
        });
    }

    /**
     * Resolve path.
     *
     * @param  string      $project
     * @param  string      $version
     * @param  string|null $page
     * @return string
     */
    protected function path($project, $version, $page = null)
    {
        $path = resource_path("docs/$project/$version");

        if (! $page) {
            return $path;
        }

        return "{$path}/$page.md";
    }
}
