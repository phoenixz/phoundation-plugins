<?php

/**
 * Class TemplateSelect
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\AdminLte
 */

declare(strict_types=1);

namespace Templates\Phoundation\AdminLte\Html\Components\Input;

use Phoundation\Utils\Arrays;
use Phoundation\Web\Html\Components\Input\InputHidden;
use Phoundation\Web\Html\Components\Input\InputSelect;
use Templates\Phoundation\Mdb\Html\Traits\TraitTemplateRenderBeforeAfterButtons;


class TemplateInputSelect extends TemplateInput
{
    use TraitTemplateRenderBeforeAfterButtons;


    /**
     * Select class constructor
     */
    public function __construct(InputSelect $component)
    {
        $component->addClasses('form-control');
        parent::__construct($component);
    }


    /**
     * @inheritDoc
     */
    public function render(): ?string
    {
        $component = $this->component;

        // Hidden elements render as an <input hidden>
        if ($component->getHidden()) {
            // Select input have multiple values support
            $return = null;

            foreach (Arrays::force($component->getSelected()) as $key => $value) {
                $return .= InputHidden::new()
                                      ->setName($component->getName())
                                      ->setValue($key)
                                      ->render();
            }

            return $return;
        }

        if ($component->getClearButton()) {
            $component->getAttributes()->add("true", 'data-mdb-clear-button');
            $component->getAttributes()->removeKeys('clear_button');

        }

        if ($component->getSearch()) {
            $component->getAttributes()->add("true", 'data-mdb-filter');
            $component->getAttributes()->removeKeys('search');
        }

        if ($component->getCustomContent()) {
            $component->getAttributes()->removeKeys('custom_content');

            $render = '<div class="select-custom-content">
                         ' . render($component->getCustomContent()) . '
                       </div>';
        }

        $after  = $this->renderAfterButtons($component);
        $before = $this->renderBeforeButtons($component);

        if ($before or $after) {
            return '<div class="input-group mb-3">' . $before . parent::render() . isset_get($render) . $after . '</div>';
        }

        return parent::render() . isset_get($render);
    }
}
