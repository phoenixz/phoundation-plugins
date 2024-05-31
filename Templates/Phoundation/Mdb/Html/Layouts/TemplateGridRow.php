<?php

declare(strict_types=1);

namespace Templates\Mdb\Html\Layouts;

use Phoundation\Web\Html\Layouts\GridRow;
use Phoundation\Web\Html\Template\TemplateRenderer;


/**
 * Class TemplateGridRow
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\Mdb
 */
class TemplateGridRow extends TemplateRenderer
{
    /**
     * GridRow class constructor
     */
    public function __construct(GridRow $component)
    {
        parent::__construct($component);
    }


    /**
     * Render this grid row
     *
     * @return string|null
     */
    public function render(): ?string
    {
        $class        = $this->component->getClass();
        $this->render = '<div class="row' . ($class ? ' ' . $class : '') . '">';

        if ($this->component->getForm()) {
            // Return content rendered in a form
            $render = '';

            foreach ($this->component->getSource() as $column) {
                $render .= $column->render();
            }

            $this->render .= $this->component->getForm()->setContent($render)->render();
            $this->component->setForm(null);
        } else {
            foreach ($this->component->getSource() as $column) {
                $this->render .= $column->render();
            }
        }

        $this->render .= '</div>';
        return parent::render();
    }
}