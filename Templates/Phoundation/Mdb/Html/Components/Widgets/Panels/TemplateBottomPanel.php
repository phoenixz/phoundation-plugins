<?php

declare(strict_types=1);

namespace Templates\Phoundation\Mdb\Html\Components\Widgets\Panels;

use Phoundation\Core\Core;
use Phoundation\Utils\Config;
use Phoundation\Web\Html\Components\Widgets\Panels\BottomPanel;
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
 * @package Templates\Mdb
 */
class TemplateBottomPanel extends TemplateRenderer
{
    /**
     * BottomPanel class constructor
     */
    public function __construct(BottomPanel $component)
    {
        parent::__construct($component);
    }


    /**
     * @inheritDoc
     */
    public function render(): ?string
    {
        if (Config::getBoolean('web.panels.bottom.enabled', true)) {
            $phoudation = '<a href="https://phoundation.org/">Phoundation</a>';
            $template   = tr('template :name', [':name' => '<a href="https://mdbootstrap.com/">' . tr('Mdb') . '</a>']);
            $project    = '<a href="' . UrlBuilder::getCurrentDomainRootUrl() . '">' . Config::getString('project.name', 'Phoundation') . '</a>';

            return '  <footer class="bg-body-tertiary text-center fixed-bottom">
                      <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
                          ' . tr(':project using :phoundation (:template)', [':project' => $project, ':phoundation' => $phoudation, ':template' => $template]) . ' ' . Core::FRAMEWORK_CODE_VERSION . '
                          <span class="float-end">' . tr('Copyright © :project', [':project' => Config::getString('project.copyright', '2024')]) . ' <a href="' . Config::getString('project.owner.url', 'https://phoundation.org') . '" target="_blank">' . Config::getString('project.owner.name', 'Phoundation') . '</a> ' . tr('All rights reserved.') . ' <br></span>
                      </div>
                  </footer>';
        }

        return null;
   }
}