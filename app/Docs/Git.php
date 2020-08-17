<?php

namespace App\Docs;

use App\Models\Project;
use Illuminate\Support\Facades\File;

class Git
{
    /**
     * Documentor instance.
     *
     * @var Documentor
     */
    protected $docs;

    /**
     * Create new LoadProjectBranches instance.
     *
     * @param  Documentor $docs
     * @return void
     */
    public function __construct(Documentor $docs)
    {
        $this->docs = $docs;
    }

    /**
     * Pull or clone repository.
     *
     * @param  Project $project
     * @param  string  $branch
     * @return void
     */
    public function pullOrClone(Project $project, $branch)
    {
        if ($this->docs->exists($project->name, $branch)) {
            return $this->pull($project, $branch);
        } else {
            return $this->clone($project, $branch);
        }
    }

    /**
     * Clone repository.
     *
     * @param  Project $project
     * @param  string  $branch
     * @return void
     */
    public function clone(Project $project, $branch = 'master')
    {
        if ($project->path) {
            return $this->cloneSubfolder($project, $branch, $project->path);
        } else {
            return $this->cloneRoot($project, $branch);
        }
    }

    /**
     * Pull repository.
     *
     * @param  Project $project
     * @param  string  $branch
     * @return void
     */
    public function pull(Project $project, $branch = 'master')
    {
        $path = $this->path($project, $branch);

        exec("cd {$path} && git pull");
    }

    /**
     * Clone full repository.
     *
     * @param  Project $project
     * @param  string  $branch
     * @return void
     */
    protected function cloneRoot(Project $project, $branch)
    {
        $path = $this->path($project, $branch);

        exec('
            git clone -b '.$branch.' '.$project->clone_url.' '.$path.' \
            && git remote add -f origin '.$project->clone_url.'
        ');
    }

    /**
     * Clone subfolder.
     *
     * @param  Project $project
     * @param  string  $branch
     * @param  string  $folder
     * @return void
     */
    protected function cloneSubfolder(Project $project, $branch, $folder)
    {
        $path = $this->path($project, $branch);

        File::ensureDirectoryExists($path);
        exec('
            cd '.$path.' \
            && git init \
            && git remote add -f origin '.$project->clone_url.' \
            && git config core.sparseCheckout true \
            && echo "/'.$folder.'" >> .git/info/sparse-checkout \
            && git checkout '.$branch.' \
            && git pull origin '.$branch.'
        ');
    }

    /**
     * Get docs path.
     *
     * @param  Project $project
     * @param  string  $branch
     * @return string
     */
    protected function path(Project $project, $branch)
    {
        $path = "resources/docs/{$project->name}/{$branch}";

        if (! app()->runningInConsole()) {
            $path = '../'.$path;
        }

        return $path;
    }
}
