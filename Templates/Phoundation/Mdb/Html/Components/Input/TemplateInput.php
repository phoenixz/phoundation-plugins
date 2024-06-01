<?php

/**
 * Class TemplateInput
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\Mdb
 */

declare(strict_types=1);

namespace Templates\Phoundation\Mdb\Html\Components\Input;

use Phoundation\Web\Html\Components\Input\InputHidden;
use Phoundation\Web\Html\Components\Input\Interfaces\InputInterface;
use Phoundation\Web\Html\Template\TemplateRenderer;
use Templates\Phoundation\Mdb\Html\Traits\TraitTemplateRenderBeforeAfterButtons;


class TemplateInput extends TemplateRenderer
{
    use TraitTemplateRenderBeforeAfterButtons;


    /**
     * Input class constructor
     */
    public function __construct(InputInterface $component)
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
        $component = $this->component;

        // Hidden elements render as an <input hidden>
        if ($component->getHidden()) {
            return InputHidden::new()
                ->setName($component->getName())
                ->setValue($component->getValue())
                ->render();
        }

        $after  = $this->renderAfterButtons($component);
        $before = $this->renderBeforeButtons($component);

        if ($before or $after) {
            // Place buttons in an input group
            return $before . parent::render() . isset_get($render) . $after;
        }

        return parent::render();
    }
}