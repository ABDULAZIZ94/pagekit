<?php

namespace Pagekit\System\Event;

use Pagekit\Framework\Event\EventSubscriber;
use Pagekit\Menu\Event\ActiveMenuEvent;

class FrontpageListener extends EventSubscriber
{
    /**
     * Registers frontpage route
     */
    public function onInit()
    {
        if ($frontpage = $this('config')->get('app.frontpage')) {
            $app = self::$app;
            $this('router')->getUrlAliases()->register('/', $this('system.info')->resolveUrl($frontpage));
            $this('router')->get('/', '@frontpage', function() use ($app) {
                $app->abort(404);
            });
        }
    }

    /**
     * Activates frontpage menu items
     *
     * @param ActiveMenuEvent $event
     */
    public function onSystemMenu(ActiveMenuEvent $event)
    {
        if ($this('request')->getPathInfo() == '/') {
            foreach ($event->get('@frontpage') as $id => $item) {
                $event->add($id);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            'init'        => array('onInit', 8),
            'system.menu' => 'onSystemMenu'
        );
    }
}
