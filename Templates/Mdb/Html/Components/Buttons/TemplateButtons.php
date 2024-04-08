<?php

declare(strict_types=1);

namespace Templates\Mdb\Html\Components\Input\Buttons\Buttons;

use Phoundation\Web\Html\Template\TemplateRenderer;


/**
 * Class TemplateButtons
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\Mdb
 */
class TemplateButtons extends TemplateRenderer
{
    /**
     * Buttons class constructor
     */
    public function __construct(\Phoundation\Web\Html\Components\Input\Buttons\Buttons $element)
    {
        parent::__construct($element);
    }


    /**
     * Renders and returns the buttons HTML
     *
     * @return string|null
     */
    public function render(): ?string
    {
        $this->render = '';

        if ($this->component->getGroup()) {
            $this->render .= '<div class="btn-group" role="group" aria-label="Button group">';
        }

        foreach ($this->component->getSource() as $button) {
            $this->render .= $button->render(). ' ';
        }

        if ($this->component->getGroup()) {
            $this->render .= '</div>';
        }

        return parent::render();
    }
}