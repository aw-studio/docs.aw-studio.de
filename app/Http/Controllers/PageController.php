<?php

namespace App\Http\Controllers;

use Fjord\Support\Facades\Form;
use GuzzleHttp\Client;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Support\Facades\Gate;

class PageController
{
    /**
     * Cache instance.
     *
     * @var Cache
     */
    protected $cache;

    /**
     * Create new PageController instance.
     *
     * @param  Cache $cache
     * @return void
     */
    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Home page.
     *
     * @return void
     */
    public function home()
    {
        return view('pages.home', [
            'page'  => Form::load('pages', 'home'),
            'repos' => $this->getRepositories(),
        ]);
    }

    /**
     * Get repositories.
     *
     * @return array
     */
    protected function getRepositories()
    {
        $repos = config('docdress.repos');

        foreach ($repos as $repo => $config) {
            // if (Gate::has("docdress.{$repo}")) {
            //     unset($repos[$repo]);
            //     continue;
            // }
            $repos[$repo]['downloads'] = $this->getRepoDownloads($repo);
            $repos[$repo]['stars'] = $this->getRepoStars($repo);
        }

        return $repos;
    }

    /**
     * Get repository downloads.
     *
     * @param  string $repo
     * @return int
     */
    protected function getRepoDownloads(string $repo)
    {
        return $this->cache->remember("downloads.{$repo}", 60 * 60, function () use ($repo) {
            $composer = config("docdress.repos.{$repo}.composer");
            if (! $composer) {
                return 0;
            }
            $client = new Client;
            $response = $client->get("https://packagist.org/packages/{$composer}.json");
            $stats = json_decode($response->getBody(), true)['package'];

            return $stats['downloads']['total'] ?? 0;
        });
    }

    /**
     * Get repository stars.
     *
     * @param  string $repo
     * @return int
     */
    protected function getRepoStars(string $repo)
    {
        return $this->cache->remember("stars.{$repo}", 60 * 60, function () use ($repo) {
            if (config("docdress.repos.{$repo}.access_token")) {
                return 0;
            }
            $client = new Client;
            $response = $client->get("https://api.github.com/repos/{$repo}");
            $stats = json_decode($response->getBody(), true);

            return $stats['stargazers_count'] ?? 0;
        });
    }
}
