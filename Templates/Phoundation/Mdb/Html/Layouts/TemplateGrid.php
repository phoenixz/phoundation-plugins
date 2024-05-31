<?php

declare(strict_types=1);

namespace Templates\Mdb\Html\Layouts;

use Phoundation\Web\Html\Layouts\Grid;
use Phoundation\Web\Html\Template\TemplateRenderer;


/**
 * Class TemplateGrid
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\Mdb
 */
class TemplateGrid extends TemplateRenderer
{
    /**
     * Grid class constructor
     */
    public function __construct(Grid $component)
    {
        parent::__construct($component);
    }


    /**
     * Render the HTML for this grid
     *
     * @return string|null
     */
    public function render(): ?string
    {
        $class        = $this->component->getClass();
        $this->render = '<div class="container-fluid' . ($class ? ' ' . $class : '') . '">';

        if ($this->component->getForm()) {
            // Return content rendered in a form
            $render = '';

            foreach ($this->component->getSource() as $row) {
                $render .= $row->render();
            }

            $this->render .= $this->component->getForm()->setContent($render)->render();
            $this->component->setForm(null);
        } else {
            foreach ($this->component->getSource() as $row) {
                $this->render .= $row->render();
            }
        }

        $this->render .= '</div>';
        return parent::render();
    }
}