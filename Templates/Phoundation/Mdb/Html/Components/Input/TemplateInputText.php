<?php

declare(strict_types=1);

namespace Templates\Phoundation\Mdb\Html\Components\Input;

use Phoundation\Web\Html\Components\Input\InputText;


/**
 * Class TemplateInputText
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\Mdb
 */
class TemplateInputText extends TemplateInput
{
    /**
     * InputText class constructor
     */
    public function __construct(InputText $component)
    {
        $component->addClasses('form-control');
        parent::__construct($component);
    }


    /**
     * Renders this input element
     *
     * @return string|null
     */
    public function render(): ?string
    {
        $return = parent::render();
        $icon   = $this->component->getIcon();

        if ($icon) {
            // Add an icon
            $return = $icon->render() . ' ' . $return;
        }

        if ($this->component->getClearButton()) {
            // Add a clear button
            $return .= '<span class="trailing pe-auto clear d-none" tabindex="0">✕</span>';
        }


        return $return;
    }
}