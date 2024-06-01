<?php

/**
 * Class TemplateButtons
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\AdminLte
 */

declare(strict_types=1);

namespace Templates\Phoundation\AdminLte\Html\Components\Input\Buttons;

use Phoundation\Web\Html\Components\Input\Buttons\Buttons;
use Phoundation\Web\Html\Template\TemplateRenderer;


class TemplateButtons extends TemplateRenderer
{
    /**
     * Buttons class constructor
     */
    public function __construct(Buttons $component)
    {
        parent::__construct($component);
    }


    /**
     * Renders and returns the buttons HTML
     *
     * @return string|null
     */
    public function render(): ?string
    {
        $render       = [];
        $this->render = '';

        if ($this->component->getGroup()) {
            $this->render .= '<div class="btn-group" role="group" aria-label="Button group">';
        }

        foreach ($this->component->getSource() as $button) {
            if (is_string($button)) {
                $render[] = $button;
            } else {
                $render[] = $button->render();
            }
        }

        $this->render = implode(' ', $render);

        if ($this->component->getGroup()) {
            $this->render .= '</div>';
        }

        return parent::render();
    }
}