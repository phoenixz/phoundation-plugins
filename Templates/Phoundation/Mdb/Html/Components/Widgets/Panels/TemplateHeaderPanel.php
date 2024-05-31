<?php

declare(strict_types=1);

namespace Templates\Mdb\Html\Components\Widgets\Panels;

use Phoundation\Exception\OutOfBoundsException;
use Phoundation\Web\Html\Html;
use Phoundation\Web\Html\Template\TemplateRenderer;
use Phoundation\Web\Requests\Response;


/**
 * Class HeaderPanel
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\Mdb
 */
class TemplateHeaderPanel extends TemplateRenderer
{
    /**
     * HeaderPanel class constructor
     */
    public function __construct(\Phoundation\Web\Html\Components\Widgets\Panels\HeaderPanel $component)
    {
        parent::__construct($component);
    }


    /**
     * @inheritDoc
     */
    public function render(): ?string
    {
        if ($this->component->getMini()) {
            return '<section class="content-header"></section>';
        }

        $title       = Response::getHeaderTitle();
        $sub_title   = Response::getHeaderSubTitle();
        $breadcrumbs = Response::getBreadCrumbs()?->render();

        if (!$title) {
            throw new OutOfBoundsException(tr('Cannot render HeaderPanel, no title specified'));
        }

        return '    <section class="content-header">
                      <div class="container-fluid">
                        <div class="row mb-2">
                          <div class="col-sm-6">
                            <h1>
                              ' . Html::safe($title) . '
                              ' . ($sub_title ? '<small>' . Html::safe($sub_title) . '</small>' : '') . '
                            </h1>
                          </div>
                          <div class="col-sm-6">
                            ' . $breadcrumbs .  '
                          </div>
                        </div>
                      </div>
                    </section>';
    }
}