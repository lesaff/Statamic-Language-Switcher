<?php

namespace Statamic\Addons\LangSwitch;

use Statamic\API\URL;
use Statamic\API\Content;
use Statamic\Extend\Tags;

class LangSwitchTags extends Tags
{
    /**
     * The {{ lang_switch }} tag
     * Returns the URL for the current page based on the 'lang' parameter
     *
     * @return string|array
     */
    public function index()
    {
        $url       = $this->get('url', URL::getCurrent());
        $lang      = $this->get('lang');
        
        $uri = URL::getDefaultUri(site_locale(), $url);
        $url = Content::whereUri($uri)->in($lang)->absoluteUrl();

        return ($this->getBool('short_url')) ? URL::makeRelative($url) : $url;
    }
}
