<?php

declare(strict_types=1);

namespace Templates\Mdb\Html\Components\Widgets\Menus;

use Phoundation\Web\Html\Html;
use Phoundation\Web\Html\Template\TemplateRenderer;
use Phoundation\Web\Http\UrlBuilder;


/**
 * Class TemplateTopMenu
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\Mdb
 */
class TemplateTopMenu extends TemplateRenderer
{
    /**
     * TopMenu class constructor
     */
    public function __construct(\Phoundation\Web\Html\Components\Widgets\Menus\TopMenu $component)
    {
        parent::__construct($component);
    }


    /**
     * Render the HTML for the Mdb top menu
     *
     * @return string|null
     */
    public function render(): ?string
    {
        $return = '<ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                        </li>
                        <li class="nav-item d-none d-sm-inline-block">
                            <a href="' . Html::safe(UrlBuilder::getCurrent()) . '" class="nav-link">' . tr('Home') . '</a>
                        </li>';

        if ($this->component->getSource()) {
            foreach ($this->component->getSource() as $label => $entry) {
                if (is_string($entry))  {
                    $entry = ['url' => $entry];
                }

                $return .= '<li class="nav-item d-none d-sm-inline-block">
                                <a href="' . Html::safe($entry['url']) . '" class="nav-link">' . Html::safe($label) . '</a>
                            </li>';
            }
        }

        return $return . '</ul>';;
    }
}