<?php

declare(strict_types=1);

namespace Templates\Mdb\Html\Components\Input;

use Phoundation\Web\Html\Components\Input\InputSelect;


/**
 * Class TemplateSelect
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\Mdb
 */
class TemplateInputSelect extends TemplateInput
{
    /**
     * Select class constructor
     */
    public function __construct(InputSelect $component)
    {
        $component->addClasses('col-sm-' . $component->getDefinition()->getSize());
        $component->addClasses('form-control');
        $component->getAttributes()->add('', 'data-mdb-select-init');
        parent::__construct($component);
    }


    /**
     * @inheritDoc
     */
    public function render(): ?string
    {
        if ($this->component->getClearButton()) {
            $this->component->getAttributes()->add("true", 'data-mdb-clear-button');
            $this->component->getAttributes()->removeKeys('clear_button');

        }

        if ($this->component->getSearch()) {
            $this->component->getAttributes()->add("true", 'data-mdb-filter');
            $this->component->getAttributes()->removeKeys('search');
        }

        if ($this->component->getCustomContent()) {
            $this->component->getAttributes()->removeKeys('custom_content');

            $render = '<div class="select-custom-content">
                         ' . render($this->component->getCustomContent()) . '
                       </div>';
        }

        return parent::render() . isset_get($render);
    }
}
