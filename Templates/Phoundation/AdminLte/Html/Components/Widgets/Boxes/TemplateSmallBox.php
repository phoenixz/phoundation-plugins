<?php

declare(strict_types=1);

namespace Templates\Phoundation\AdminLte\Html\Components\Widgets\Boxes;

use Phoundation\Web\Html\Html;
use Phoundation\Web\Html\Template\TemplateRenderer;


/**
 * Class TemplateSmallBox
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\AdminLte
 */
class TemplateSmallBox extends TemplateRenderer
{
    /**
     * SmallBox class constructor
     */
    public function __construct(\Phoundation\Web\Html\Components\Widgets\Boxes\SmallBox $component)
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
        $this->render = '   <div class="small-box bg-' . Html::safe($this->component->getMode()->value) . ($this->component->getShadow() ? ' ' . Html::safe($this->component->getShadow()) : '') . '">
                              <div class="inner">
                                <h3>' . Html::safe($this->component->get()) . '</h3>       
                                <p>' . Html::safe($this->component->getTitle()) . '</p>
                              </div>
                              ' . (($this->component->getProgress() !== null) ? '   <div class="progress">
                                                                                    <div class="progress-bar" style="width: ' . $this->component->getProgress() . '%"></div>
                                                                                  </div>' : '') . '
                              ' . ($this->component->getDescription() ? '<p>' . Html::safe($this->component->getDescription()) . '</p>' : '') . '                        
                              ' . ($this->component->getIcon() ? '  <div class="icon">
                                                        <i class="fas ' . Html::safe($this->component->getIcon()) . '"></i>
                                                    </div>' : '') . '
                              ' . ($this->component->getUrl() ? ' <a href="' . Html::safe($this->component->getUrl()) . '" class="small-box-footer">
                                                    ' . tr('More info') . ' <i class="fas fa-arrow-circle-right"></i>
                                                  </a>' : '') . '                        
                            </div>';

        return parent::render();
    }
}