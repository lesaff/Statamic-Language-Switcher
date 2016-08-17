<?php

namespace Statamic\Addons\LangSwitch;

use Statamic\API\Config;
use Statamic\API\Content;
use Statamic\API\Page;
use Statamic\API\URL;
use Statamic\API\unlocalize;
use Statamic\Extend\Tags;
use Statamic\Contracts\Data\Entries\Entry as EntryContract;

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
        // Get parameters
        $url      = $this->get('url', URL::getCurrent());
        $lang     = $this->get('lang');
        $short_url = $this->get('short_url');
        
        $full_url = ($short_url) ? false : true;
        return $this->getLocalizedUrl($url, $lang, $full_url);
    }


    private function getLocalizedUrl($url, $lang = null, $full_url = false)
    {
        //get base-url (default language one)
        $base_url = URL::unlocalize($url, site_locale()); 
        
        $data = Content::whereUri($base_url);
        $content = $data->in($lang);
        $pageId = $data->id();

        if ($data instanceof EntryContract)
        {
            $collection = $data->collectionName();
            $routes = array_get(Config::getRoutes(), 'collections', []);
            if (! $route = array_get($routes, $collection)) {
                $uri = "";
            }

            // Would be nice if this could be accomplished trough the API classes....
            $uri = app('Statamic\Contracts\Data\Content\UrlBuilder')->content($content)->build($route);
        } else {
            
            // Would be nice if this could be accomplished trough the API classes....
            $uri = app('PagesService')->localizedUris($lang)->get($pageId);
        }
        
        
        if ($full_url) {
            $uri = URL::prependSiteUrl($uri, $lang, true);
        } else {
            $uri = URL::prependSiteRoot($uri, $lang, true);
        }

        return $uri; 
    }



}
