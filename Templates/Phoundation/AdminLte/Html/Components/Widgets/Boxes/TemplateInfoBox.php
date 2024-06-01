<?php

declare(strict_types=1);

namespace Templates\Phoundation\AdminLte\Html\Components\Widgets\Boxes;

use Phoundation\Web\Html\Html;
use Phoundation\Web\Html\Template\TemplateRenderer;


/**
 * Class TemplateInfoBox
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\AdminLte
 */
class TemplateInfoBox extends TemplateRenderer
{
    /**
     * InfoBox class constructor
     */
    public function __construct(\Phoundation\Web\Html\Components\Widgets\Boxes\InfoBox $component)
    {
        parent::__construct($component);
    }


    /**
     * Renders and returns the HTML for this SmallBox object
     *
     * @inheritDoc
     */
    public function render(): ?string
    {
        $this->render = '   <div class="info-box shadow-none">
                              <span class="info-box-icon bg-' . Html::safe($this->component->getMode()->value) . '"><i class="far ' . Html::safe($this->component->getIcon()) . '"></i></span>
                
                              <div class="info-box-content">
                                <span class="info-box-text">' . Html::safe($this->component->getTitle()) . '</span>
                                <span class="info-box-number">' . Html::safe($this->component->get()) . '</span>
                              </div>
                              ' . Html::safe($this->component->getDescription()) . '
                            </div>';

        return parent::render();
    }
}