<?php

declare(strict_types=1);

namespace Templates\Phoundation\Mdb\Html\Components\Widgets\Boxes;

use Phoundation\Web\Html\Html;
use Phoundation\Web\Html\Template\TemplateRenderer;


/**
 * Class TemplateInfoBoxBg
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\Mdb
 */
class TemplateInfoBoxBg extends TemplateRenderer
{
    /**
     * InfoBoxBg class constructor
     */
    public function __construct(\Phoundation\Web\Html\Components\Widgets\Boxes\InfoBoxBg $component)
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
        $this->render = '   <div class="info-box bg-' . Html::safe($this->component->getMode()->value) . '">
                              <span class="info-box-icon"><i class="far ' . Html::safe($this->component->getIcon()) . '"></i></span>
                
                              <div class="info-box-content">
                                <span class="info-box-text">' . Html::safe($this->component->getTitle()) . '</span>
                                <span class="info-box-number">' . Html::safe($this->component->get()) . '</span>
                
                                ' . (($this->component->getProgress() !== null) ? ' <div class="progress">
                                                                                    <div class="progress-bar" style="width: ' . Html::safe($this->component->getProgress()) . '%"></div>
                                                                                  </div>' : '') . '
                                <span class="progress-description">
                                  ' . Html::safe($this->component->getDescription()) . '
                                </span>
                              </div>
                              <!-- /.info-box-content -->
                            </div>';

        return parent::render();
    }
}