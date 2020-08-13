<?php

namespace App\Docs;

use Illuminate\Support\Str;
use ParsedownExtra;

class Parser extends ParsedownExtra
{
    /**
     * Parse markdown to html.
     *
     * @param  string $text
     * @return string
     */
    public function text($text)
    {
        $text = $this->buildToc($text);

        $text = $this->alerts($text);

        $text = parent::text($text);

        $text = $this->defaultCodeToPhp($text);

        $text = $this->parseLinks($text);

        return $this->parseLinkNames($text);
    }

    /**
     * Parse links.
     *
     * @param  string $text
     * @return string
     */
    public function parseLinks($text)
    {
        preg_match_all('/(?<=\bhref=")[^"]*/', $text, $matches);

        foreach ($matches[0] as $link) {
            if (Str::startsWith($link, '#')) {
                continue;
            }

            if (array_key_exists('host', parse_url($link))) {
                $replace = "{$link}\" target=\"_blank";
            } else {
                $split = explode('/', request()->getPathInfo());
                array_pop($split);

                $replace = str_replace('.md', '', '/'.trim(implode('/', $split).'/'.$link, '/'));
            }

            $text = str_replace($link, $replace, $text);
        }

        return $text;
    }

    /**
     * Parse links.
     *
     * @see https://github.com/laravel/laravel.com-next
     *
     * @param  string $text
     * @return string
     */
    protected function parseLinkNames($text)
    {
        $lines = explode("\n", $text);
        foreach ($lines as $number => $line) {
            preg_match('/<a name="(.+)">/', $line, $matches);

            if (isset($matches[1])) {
                $name = $matches[1];

                if (isset($lines[$number + 1]) && Str::startsWith($lines[$number + 1], '<h')) {
                    $header = substr_replace($lines[$number + 1], sprintf(' id="%s"', $name), 3, 0);
                    $lines[$number + 1] = $header;
                }
            }
        }

        return implode("\n", $lines);
    }

    /**
     * Build table of contents.
     *
     * @param  string $text
     * @return string
     */
    protected function buildToc($text)
    {
        $toc = [];
        preg_match_all('/(?m)^#{2,3}(?!#)(.*)/', $text, $matches);

        foreach ($matches[1] as $key => $heading) {
            $slug = Str::slug($heading);
            $replace = '<a name="'.$slug.'"></a>
'.str_replace($heading, ' <a href="#'.$slug.'">'.$heading."</a>\n", $matches[0][$key]);

            $text = str_replace($matches[0][$key]."\n", $replace, $text);

            $link = '['.trim($heading)."](#{$slug})";

            if (Str::startsWith($matches[0][$key], '###')) {
                $toc[] = "    - {$link}";
            } else {
                $toc[] = "- {$link}";
            }
        }

        return preg_replace('/(?m)^#{1}(?!#)(.*)/', "$0\n\n".implode("\n", $toc), $text);
    }

    /**
     * Set default code to PHP.
     *
     * @param  string $text
     * @return string
     */
    protected function defaultCodeToPhp($text)
    {
        return str_replace('<code>', '<code class="language-php">', $text);
    }

    /**
     * Parse allerts.
     *
     * @param  string $text
     * @return string
     */
    protected function alerts($text)
    {
        preg_match_all('/:::(?s)(.*?):::/', $text, $match);

        foreach ($match[1] as $key => $block) {
            $split = explode("\n", $block);
            $name = trim(array_shift($split));
            $content = parent::text(implode("\n", $split));
            $text = Str::replaceFirst($match[0][$key], "<div class=\"alert alert-{$name}\">{$content}</div>", $text);
        }

        return $text;
    }
}
