<?php

declare(strict_types=1);

namespace Templates\Mdb\Html\Components\Widgets;

use Phoundation\Utils\Strings;
use Phoundation\Web\Html\Html;
use Phoundation\Web\Html\Template\TemplateRenderer;
use Phoundation\Web\Http\UrlBuilder;


/**
 * Class TemplateBreadCrumbs
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\Mdb
 */
class TemplateBreadCrumbs extends TemplateRenderer
{
    /**
     * BreadCrumbs class constructor
     */
    public function __construct(\Phoundation\Web\Html\Components\Widgets\BreadCrumbs $component)
    {
        parent::__construct($component);
    }


    /**
     * Renders and returns the HTML for this component
     *
     * @return string|null
     */
    public function render(): ?string
    {
        $this->render = ' <ol class="breadcrumb float-sm-right">';

        if ($this->component->getSource()) {
            $count = count($this->component->getSource());

            foreach ($this->component->getSource() as $url => $label) {
                $label = Strings::truncate($label, 48);

                if (!--$count) {
                    // The last item is the active item
                    $this->render .= '<li class="breadcrumb-item active">' . Html::safe($label) . '</li>';

                } else {
                    $this->render .= '<li class="breadcrumb-item"><a href="' . Html::safe(UrlBuilder::getWww($url)) . '">' . Html::safe($label) . '</a></li>';
                }
            }
        }

        $this->render .= '</ol>';

        return parent::render();
    }
}