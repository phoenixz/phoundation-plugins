<?php

declare(strict_types=1);

namespace Templates\AdminLte\Html\Components\Forms;

use Phoundation\Core\Log\Log;
use Phoundation\Data\DataEntry\Definitions\Interfaces\DefinitionInterface;
use Phoundation\Exception\OutOfBoundsException;
use Phoundation\Web\Html\Components\Forms\Interfaces\DataEntryFormColumnInterface;
use Phoundation\Web\Html\Components\Widgets\Tooltips\Tooltip;
use Phoundation\Web\Html\Html;
use Phoundation\Web\Html\Template\TemplateRenderer;
use Phoundation\Web\Requests\Response;


/**
 * Class DataEntryForm
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\AdminLte
 */
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
        $scripts = '';
        $definition = $this->component->getDefinition();
        $component  = $this->component->getColumnComponent();

        if (!$definition) {
            throw new OutOfBoundsException(tr('Cannot render form component, no definition specified'));
        }

        if (!$component) {
            throw new OutOfBoundsException(tr('Cannot render form component, no component specified'));
        }

        if (is_object($component)) {
            $component = $component->render();
        }

        // Add scripts?
        if ($definition->getScripts()) {
            foreach ($definition->getScripts() as $script) {
                $scripts .= $script->render();
            }
        }

        if ($definition->getHidden()) {
            // Hidden elements don't display anything beyond the hidden <input>
            return $component . $scripts;
        }

        $this->render .= match ($definition->getInputType()?->value) {
            'checkbox' => '    <div class="col-sm-' . Html::safe($definition->getSize() ?? 12) . '">
                                   <div class="form-group">
                                       <div class="form-horizontal">
                                           <label for="' . Html::safe($definition->getColumn()) . '">' . Html::safe($definition->getLabel()) . '</label>
                                           ' . $this->renderTooltip($definition) . '
                                       </div>
                                       <div class="form-check">
                                           ' . $component . $scripts . '
                                           <label class="form-check-label" for="' . Html::safe($definition->getColumn()) . '">' . Html::safe($definition->getLabel()) . '</label>
                                       </div>
                                   </div>
                               </div>',

            default    => '    <div class="col-sm-' . Html::safe($definition->getSize() ?? 12) . '">
                                   <div class="form-group">
                                       <div class="form-horizontal">
                                           <label for="' . Html::safe($definition->getColumn()) . '">' . Html::safe($definition->getLabel()) . '</label>
                                           ' . $this->renderTooltip($definition) . '
                                       </div>
                                       ' . $component . $scripts . '
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