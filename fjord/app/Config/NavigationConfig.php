<?php

namespace FjordApp\Config;

use Fjord\Application\Navigation\Config;
use Fjord\Application\Navigation\Navigation;

class NavigationConfig extends Config
{
    /**
     * Topbar navigation entries.
     *
     * @param \Fjord\Application\Navigation\Navigation $nav
     *
     * @return void
     */
    public function topbar(Navigation $nav)
    {
        $nav->section([
            $nav->preset('profile'),
        ]);

        $nav->section([
            $nav->title(__f('fj.user_administration')),

            $nav->preset('user.user', [
                'icon' => fa('users'),
            ]),
            $nav->preset('permissions'),
        ]);

        $nav->section([
            $nav->preset('form.collections.settings', [
                'icon' => fa('cog'),
            ]),
        ]);
    }

    /**
     * Main navigation entries.
     *
     * @param  \Fjord\Application\Navigation\Navigation $nav
     * @return void
     */
    public function main(Navigation $nav)
    {
        $nav->section([
            $nav->title('Models'),

            $nav->preset('crud.user', [
                'icon' => fa('users'),
            ]),
            $nav->preset('crud.project', [
                'icon' => fa('book-open'),
            ]),
            $nav->preset('crud.access', [
                'icon' => fa('key'),
            ]),
        ]);
    }
}
