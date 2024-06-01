<?php

declare(strict_types=1);

namespace Templates\Phoundation\AdminLte;

use Phoundation\Core\Plugins\Plugins;
use Phoundation\Utils\Config;
use Phoundation\Web\Html\Components\Widgets\Panels\BottomPanel;
use Phoundation\Web\Html\Components\Widgets\Panels\HeaderPanel;
use Phoundation\Web\Html\Components\Widgets\Panels\Interfaces\PanelsInterface;
use Phoundation\Web\Html\Components\Widgets\Panels\Panels;
use Phoundation\Web\Html\Components\Widgets\Panels\SidePanel;
use Phoundation\Web\Html\Components\Widgets\Panels\TopPanel;
use Phoundation\Web\Html\Html;
use Phoundation\Web\Requests\Request;
use Phoundation\Web\Requests\Response;


/**
 * class TemplatePage
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\AdminLte
 */
class TemplatePage extends \Phoundation\Web\Html\Template\TemplatePage
{
    /**
     * Execute, builds and returns the page output, according to the template.
     *
     * Either use the default execution steps from parent::execute($target), or write your own execution steps here.
     * Once the output has been generated, it should be returned.
     *
     * @return string|null
     */
    public function execute(): ?string
    {
        Request::setPanelsObject($this->getAvailablePanelsObject());
        Plugins::start();

        $body = $this->renderBody();

        if (Request::getMainContentsOnly()) {
            return $body;
        }

        // Build HTML and minify the output
        $output = $this->renderHtmlHeadTag();
        Response::getHtmlHeadersSent(true);

        if (Response::getBuildBodyWrapper()) {
            $output .= ' <body class="sidebar-mini' . (Config::get('web.panels.sidebar.collapsed', false) ? ' sidebar-collapse' : '') . '" style="height: auto;">
                            <div class="wrapper">' .
                                Response::getFlashMessages()->render() .
                                Request::getPanelsObject()->get('top', false)?->render() .
                                Request::getPanelsObject()->get('left')?->render() .
                                $body .
                                Request::getPanelsObject()->get('bottom', false)?->render() . '
                            </div>';
        } else {
            // Page requested that no body parts be built
            $output .= $body;
        }

        $output .= $this->renderHtmlFooters();
        $output  = Html::minify($output);

        // Build Template specific HTTP headers
        $this->renderHttpHeaders($output);
        return $output;
    }


    /**
     * Returns a Panels object with the available panels for this Template
     *
     * @return PanelsInterface
     */
    public function getAvailablePanelsObject(): PanelsInterface
    {
        return Panels::new()
            ->add(Config::getBoolean('web.panels.top.enabled'   , true) ? TopPanel::new()    : null, 'top')
            ->add(Config::getBoolean('web.panels.left.enabled'  , true) ? SidePanel::new()   : null, 'left')
            ->add(Config::getBoolean('web.panels.header.enabled', true) ? HeaderPanel::new() : null, 'header')
            ->add(Config::getBoolean('web.panels.bottom.enabled', true) ? BottomPanel::new() : null, 'bottom');
    }


    /**
     * Build the HTTP headers for the page
     *
     * @param string $output
     * @return void
     */
    public function renderHttpHeaders(string $output): void
    {
        Response::setContentType('text/html');
        Response::setDoctype('html');
    }


    /**
     * Build the HTML header for the page
     *
     * @return string|null
     */
    public function renderHtmlHeadTag(): ?string
    {
        // Set head meta data
        Response::setFavIcon();
        Response::setViewport('width=device-width, initial-scale=1');

        // Load basic AdminLte and fonts CSS
        Response::loadCss([
            'https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback',
            'Phoundation/adminlte/plugins/fontawesome-free-6.4.0-web/css/all',
            'Phoundation/adminlte/plugins/fontawesome-free-6.4.0-web/css/regular',
//            'Phoundation/adminlte/plugins/fontawesome-free-6.4.0-web/css/v4-shim',
            'Phoundation/adminlte/css/adminlte',
            'Phoundation/adminlte/plugins/overlayScrollbars/css/OverlayScrollbars',
            'Phoundation/adminlte/css/phoundation'
        ], true);

        // Load configured CSS files
        Response::loadCss(Config::getArray('templates.adminlte.css', []));

        // Load basic AdminLte amd jQuery javascript libraries
        Response::loadJavascript([
            'Phoundation/adminlte/plugins/jquery/jquery',
            'Phoundation/adminlte/plugins/jquery-ui/jquery-ui',
            'Phoundation/adminlte/plugins/bootstrap/js/bootstrap.bundle',
            'Phoundation/adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars',
            'Phoundation/adminlte/js/adminlte'
        ], prefix: true);

        // Set basic page details
        Response::setPageTitle(Config::get('project.name', tr('Phoundation project')) . ' (' . Response::getHeaderTitle() . ')');

        return Response::renderHtmlHeaders();
    }


    /**
     * Build the HTML body
     *
     * @return string|null
     */
    public function renderBody(): ?string
    {
        $body = parent::renderBody();

        if (Request::getMainContentsOnly()) {
            return $body;
        }

        if (Response::getBuildBodyWrapper()) {
            $body = '   <div class="' . Response::getClass('content-wrapper', 'content-wrapper') .  '" style="min-height: 1518.06px;">
                           ' . Request::getPanelsObject()->get('header', false)?->render() . '
                            <section class="content">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-md-12">
                                            ' . $body . '
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>';
        }

        return $body;
    }
}
