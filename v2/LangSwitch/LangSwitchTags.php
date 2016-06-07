<?php

namespace Statamic\Addons\LangSwitch;

use Statamic\Extend\Tags;
use Statamic\API\URL;

class LangSwitchTags extends Tags
{
    /**
     * The {{ lang_switch }} tag
     *
     * @return string|array
     */
    public function index()
    {
        // Get parameters
        $url      = $this->get('url', URL::getCurrent());
        $lang     = $this->get('lang');
        $segment  = $this->getInt('segment', 1);
        $full_url = $this->get('full_url');

        // Don't do anything if there's no language specified
        if (!$lang) {
            return $url;
        }

        // Remove the site URL
        $site_url = URL::getSiteUrl();
        $url      = str_replace($site_url, '', $url);


        // Swap out the segment
        $segments = explode('/', $url);
        array_splice($segments, $segment, 0, $lang);
        $url = join($segments, '/');

        // Make it a full URL if required
        if ($full_url) {
            $url = $site_url . $url;
        }

        return $url;
    }
}
