<?php

/**
 * Class TemplateDataEntryFormColumn
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\AdminLte
 */

declare(strict_types=1);

namespace Templates\Phoundation\AdminLte\Html\Components\Forms;

use Phoundation\Data\DataEntry\Definitions\Interfaces\DefinitionInterface;
use Phoundation\Exception\OutOfBoundsException;
use Phoundation\Web\Html\Components\Forms\Interfaces\DataEntryFormColumnInterface;
use Phoundation\Web\Html\Components\Widgets\Tooltips\Tooltip;
use Phoundation\Web\Html\Html;
use Phoundation\Web\Html\Template\TemplateRenderer;

class TemplateDataEntryFormColumn extends TemplateRenderer
{
    /**
     * FilterForm class constructor
     */
    public function __construct(DataEntryFormColumnInterface $component)
    {
        parent::__construct($component);
    }


    public function render(): ?string
    {
        $definition = $this->component->getDefinition();
        $component  = $this->component->getColumnComponent();
        $scripts    = '';

        if (!$definition) {
            throw new OutOfBoundsException(tr('Cannot render form component, no definition specified'));
        }

        if (!$component) {
            return null;
        }

        if (is_string($component)) {
            $render = $component;
            $group  = false;

        } else {
            $render = $component->render();
            $group  = ($component->hasBeforeButtons() or $component->hasAfterButtons());

            if ($component->hasOuterDiv()) {
                // Get attributes and properties for the outer div
                $outer      = $component->getOuterDiv();
                $class      = $outer->getClass();
                $attributes = $outer->getAttributesString();
            }
        }

        // Add scripts?
        if ($definition->getScripts()) {
            foreach ($definition->getScripts() as $script) {
                $scripts .= $script->render();
            }
        }

        if ($definition->getHidden()) {
            // Hidden elements don't display anything beyond the hidden <input>
            return $render . $scripts;
        }

        $this->render .= match ($definition->getInputType()?->value) {
            'checkbox' => '    <div class="col-sm-' . Html::safe($definition->getSize() ?? 12) . '">
                                   <div class="form-group'  . ($group ? 'input-group ' : null) . (isset($class) ? ' ' . $class : '') . '"' . (isset($attributes) ? ' ' . $attributes : '') . '>
                                       <div class="form-horizontal">
                                           <label for="' . Html::safe($definition->getColumn()) . '">' . Html::safe($definition->getLabel()) . '</label>
                                           ' . $this->renderTooltip($definition) . '
                                       </div>
                                       <div class="form-check">
                                           ' . $render . $scripts . '
                                           <label class="form-check-label" for="' . Html::safe($definition->getColumn()) . '">' . Html::safe($definition->getLabel()) . '</label>
                                       </div>
                                   </div>
                               </div>',

            default    => '    <div class="col-sm-' . Html::safe($definition->getSize() ?? 12) . '">
                                   <div class="form-group'  . ($group ? 'input-group ' : null) . (isset($class) ? ' ' . $class : '') . '"' . (isset($attributes) ? ' ' . $attributes : '') . '>
                                       <div class="form-horizontal">
                                           <label for="' . Html::safe($definition->getColumn()) . '">' . Html::safe($definition->getLabel()) . '</label>
                                           ' . $this->renderTooltip($definition) . '
                                       </div>
                                       ' . $render . $scripts . '
                                   </div>
                                </div>',
        };

        return parent::render();
    }


    /**
     * Renders and returns the tooltip for the specified definition
     *
     * @param DefinitionInterface $definition
     * @return string|null
     */
    protected function renderTooltip(DefinitionInterface $definition): ?string
    {
        if ($definition->getTooltip()) {
            // Render and return the tooltip
            return Tooltip::new()
                ->setTitle($definition->getTooltip())
                ->setUseIcon(true)
                ->render();
        }

        return null;
    }
}