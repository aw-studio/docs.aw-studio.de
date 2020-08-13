<?php

namespace App\Docs;

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

    public function __construct(Filesystem $files, Cache $cache, Parser $parser)
    {
        $this->files = $files;
        $this->cache = $cache;
        $this->parser = $parser;
    }

    /**
     * Dermines if a page exists.
     *
     * @param  string $page
     * @return bool
     */
    public function exists($page)
    {
        return $this->files->exists(
            $this->path($page)
        );
    }

    /**
     * Get index.
     *
     * @return string
     */
    public function getIndex()
    {
        $content = Str::after((new ParsedownExtra)->text(
            $this->files->get($this->path('readme'))
        ), '<h2>Index</h2>');

        preg_match_all('/(?<=\bhref=")[^"]*/', $content, $matches);

        foreach ($matches[0] as $match) {
            $page = str_replace('.md', '', $match);
            $content = str_replace($match, trim("/docs/$page"), $content);
        }

        return $content;
    }

    public function get($page)
    {
        return $this->cache->remember("docs.{$page}", 5, function () use ($page) {
            if (! $this->exists($page)) {
                return;
            }

            $content = $this->parser->text(
                $this->files->get($this->path($page))
            );

            return $content;
        });
    }

    protected function path($page)
    {
        return resource_path("docs/$page.md");
    }
}
