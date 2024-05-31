<?php

declare(strict_types=1);

namespace Templates\AdminLte\Html\Components\Widgets\Panels;

use Phoundation\Core\Core;
use Phoundation\Utils\Config;
use Phoundation\Web\Html\Template\TemplateRenderer;
use Phoundation\Web\Http\UrlBuilder;


/**
 * Class TemplateBottomPanel
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\AdminLte
 */
class TemplateBottomPanel extends TemplateRenderer
{
    /**
     * BottomPanel class constructor
     */
    public function __construct(\Phoundation\Web\Html\Components\Widgets\Panels\BottomPanel $component)
    {
        parent::__construct($component);
    }


    /**
     * @inheritDoc
     */
    public function render(): ?string
    {
        $phoudation = '<a href="https://phoundation.org/">Phoundation</a>';
        $adminlte   = tr('template :name', [':name' => '<a href="https://adminlte.io/">' . tr('AdminLte') . '</a>']);
        $project    = '<a href="' . UrlBuilder::getCurrentDomainRootUrl() . '">' . Config::getString('project.name', 'Phoundation') . '</a>';

        return '  <footer class="main-footer">
                    <div class="float-right d-none d-sm-block">
                      <b>' . tr(':project using :phoundation (:adminlte)', [':project' => $project, ':phoundation' => $phoudation, ':adminlte' => $adminlte]) . '</b> ' . Core::FRAMEWORK_CODE_VERSION . '
                    </div>
                    <strong>Copyright © ' . Config::getString('project.copyright', '2024') . ' <a href="' . Config::getString('project.owner.url', 'https://phoundation.org') . '" target="_blank">' . Config::getString('project.owner.name', 'Phoundation') . '</a>.</strong> All rights reserved. <br>
                  </footer>';
    }
}