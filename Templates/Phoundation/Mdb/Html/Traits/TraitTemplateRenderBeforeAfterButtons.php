<?php

/**
 * Trait TraitTemplateRenderBeforeAfterButtons
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\Mdb
 */

declare(strict_types=1);

namespace Templates\Phoundation\Mdb\Html\Traits;

use Phoundation\Web\Html\Components\Input\Interfaces\RenderInterface;


trait TraitTemplateRenderBeforeAfterButtons
{
    /**
     * Renders and returns the buttons that come before the input element
     *
     * @param RenderInterface $component
     *
     * @return string|null
     */
    public function renderBeforeButtons(RenderInterface $component): ?string
    {
        // Render before / after buttons
        if ($component->hasBeforeButtons()) {
            $buttons = $component->getBeforeButtons();

            foreach ($buttons as $button) {
                $button->addAria($component->getId() ?? $component->getName(), 'described-by');
            }

            return $buttons->render();
        }

        return null;
    }


    /**
     * Renders and returns the buttons that come after the input element
     *
     * @param RenderInterface $component
     *
     * @return string|null
     */
    public function renderAfterButtons(RenderInterface $component): ?string
    {
        // Render after / after buttons
        if ($component->hasAfterButtons()) {
            $buttons = $component->getAfterButtons();

            foreach ($buttons as $button) {
                $button->addAria($component->getId() ?? $component->getName(), 'described-by');
            }

            return $buttons->render();
        }

        return null;
    }
}