<?php

declare(strict_types=1);

namespace Templates\Mdb\Html\Components\Input;

use Phoundation\Web\Html\Html;
use Phoundation\Web\Html\Components\Input\InputMultiButtonText;


/**
 * Class TemplateInputMultiButtonText
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\Mdb
 */
class TemplateInputMultiButtonText extends TemplateInput
{
    /**
     * InputMultiButtonText class constructor
     */
    public function __construct(InputMultiButtonText $component)
    {
        parent::__construct($component);
    }


    /**
     * Renders and returns the HTML for this object
     *
     * @return string|null
     */
    public function render(): ?string
    {
        $options = '';

        // Build the options list
        foreach ($this->component->getSource() as $url => $label) {
            if (str_starts_with($label, '#')) {
                // Any label starting with # is a divider
                $options .= '<li class="dropdown-divider"></li>';
            } else {
                $options .= '<li class="dropdown-item"><a href="' . Html::safe($url) . '">' . Html::safe($label) . '</a></li>';
            }
        }

        // Render the entire object
        $this->render = '   <div class="input-group input-group-lg mb-3">
                                <div class="input-group-prepend">
                                ' . $this->component->getButton()->render() . '                                
                                <ul class="dropdown-menu" style="">
                                    ' . $options . '
                                </ul>
                                </div>
                                
                                ' . $this->component->getInput()->render() . '
                            </div>';

        return parent::render();
    }
}
