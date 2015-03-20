<?php
/*
 * RSSFeedsWidget.php - Shows RSS feeds.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 2 of
 * the License, or (at your option) any later version.
 *
 * @author      Thomas Hackl <thomas.hackl@uni-passau.de>
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GPL version 2
 * @category    Stud.IP
 */

require_once('bootstrap.php');
require_once('controllers/feeds.php');
require_once('models/RSSFeed.php');

class RSSFeedsWidget extends StudIPPlugin implements PortalPlugin {

    public function getPluginName() {
        return dgettext('rssfeedswidget', 'News von anderen Webseiten');
    }

    function getPortalTemplate() {
        if ($GLOBALS['user']->id != 'nobody') {
            $trails_root = $this->getPluginPath();
            $dispatcher = new Trails_Dispatcher($this->getPluginPath(), "plugins.php", 'index');
            $controller = new FeedsController($dispatcher);
            $controller->plugin = $this;

            $response = $controller->relay('feeds/index');
            $template = $GLOBALS['template_factory']->open('shared/string');
            $template->content = $response->body;

            $subscriptions = new Navigation('', PluginEngine::getURL('rssfeedswidget/feeds/subscriptions'));
            $subscriptions->setImage('icons/16/blue/rss.png', array('data-dialog' => 'size=auto',
                'title' => dgettext('rssfeedswidget', 'Meine Streams')));
            $template->icons = array($subscriptions);
            if ($GLOBALS['perm']->have_perm('root')) {
                $settings = new Navigation('', PluginEngine::getURL('rssfeedswidget/feeds/settings'));
                $settings->setImage('icons/16/blue/admin.png', array('data-dialog' => 'size=auto',
                    'title' => dgettext('rssfeedswidget', 'Globale Einstellungen')));
                $template->icons = array($subscriptions, $settings);
            }
            if (Studip\ENV == 'development') {
                $js = $this->getPluginURL() . '/assets/javascripts/rssfeedswidget.js';
            } else {
                $js = $this->getPluginURL().'/assets/javascripts/rssfeedswidget.min.js';
            }
            PageLayout::addScript($js);

            return $template;
        }
        return NULL;
    }

}