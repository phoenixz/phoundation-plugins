<?php

/**
 * Class TemplateInputRadio
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\AdminLte
 */

declare(strict_types=1);

namespace Templates\AdminLte\Html\Components\Input;

use Phoundation\Web\Html\Components\Input\InputRadio;

class TemplateInputRadio extends TemplateInput
{
    /**
     * InputRadio class constructor
     */
    public function __construct(InputRadio $component)
    {
        $component->addClass('form-control');
        parent::__construct($component);
    }


    /**
     * Render and return the HTML for this object
     *
     * @return string|null
     */
    public function render(): ?string
    {
        $object = $this->getComponent();

        return '<div class="custom-control custom-checkbox">
                    ' . parent::render() . '
                    ' . ($object->getLabel() ? '<label for="' . $object->getId() . '" class="custom-control-label">' . $object->getLabel() . '</label>' : '') . '
                </div>';
    }
}