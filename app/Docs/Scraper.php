<?php

namespace App\Docs;

use App\Models\Project;
use Github;

class Scraper
{
    /**
     * Github API client.
     *
     * @var Github\Client
     */
    protected $client;

    /**
     * Create new Scraper instance.
     *
     * @param Github\Client $client
     */
    public function __construct(Github\Client $client)
    {
        $this->client = $client;
    }

    /**
     * Fetch branches for repo.
     *
     * @param  Project $project
     * @return array
     */
    public function branches(Project $project)
    {
        $this->authorize($project);

        return $this->client->repo()->branches(
            ...explode('/', $project->name)
        );
    }

    /**
     * Fetch repo.
     *
     * @param  Project $project
     * @return array
     */
    public function repo(Project $project)
    {
        $this->authorize($project);

        return $this->client->repo()->show(
            ...explode('/', $project->name)
        );
    }

    /**
     * Authorize repository.
     *
     * @param  Project $project
     * @return void
     */
    protected function authorize(Project $project)
    {
        if ($project->token) {
            $this->client->authenticate($project->token, Github\Client::AUTH_ACCESS_TOKEN);
        }
    }
}
