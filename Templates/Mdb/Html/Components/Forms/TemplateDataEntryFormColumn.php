<?php
/**
 * Class DataEntryForm
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\Mdb
 */

declare(strict_types=1);

namespace Templates\Mdb\Html\Components\Forms;

use Phoundation\Data\DataEntry\Definitions\Interfaces\DefinitionInterface;
use Phoundation\Exception\OutOfBoundsException;
use Phoundation\Web\Html\Components\Forms\Interfaces\DataEntryFormColumnInterface;
use Phoundation\Web\Html\Components\Widgets\Tooltips\Tooltip;
use Phoundation\Web\Html\Html;
use Phoundation\Web\Html\Template\TemplateRenderer;
use Templates\Mdb\TemplatePage;

class TemplateDataEntryFormColumn extends TemplateRenderer
{
    /**
     * FilterForm class constructor
     */
    public function __construct(DataEntryFormColumnInterface $component)
    {
        parent::__construct($component);
    }


    /**
     * Render the DataEntry Form Column
     *
     * @note $this->component is a DataEntryFormColumn object here, the component to render is inside there and can be
     *       accessed with $this->component->getColumnComponent() where (again) $this->component is actually the column,
     *       not the component itself.
     *
     * @return string|null
     */
    public function render(): ?string
    {
        $definition = $this->component->getDefinition();
        $component  = $this->component->getColumnComponent();
        $group      = ($component->hasBeforeButtons() or $component->hasAfterButtons());
        $scripts    = '';

        if (!$definition) {
            throw new OutOfBoundsException(tr('Cannot render form component, no definition specified'));
        }

        if (!$component) {
            throw new OutOfBoundsException(tr('Cannot render form component, no component specified'));
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

        switch ($definition->getElement()) {
            case 'input':
                $label    = null;
                $mdb_init = ' data-mdb-input-init=""';
                break;

            case 'select':
                $this->render .= '<div class="' . ($group ? 'input-group ' : null) . TemplatePage::getBottomMarginString() . Html::safe($definition->getSize() ? 'col-sm-' . $definition->getSize() : 'col') . ($definition->getVisible() ? '' : ' invisible') . ($definition->getDisplay() ? '' : ' d-none') . '">
                                     ' . $component->render() . $scripts .
                    ($definition->getLabel() ? ' <label class="form-label select-label" for="' . Html::safe($definition->getColumn()) . '">
                                                   ' . Html::safe($definition->getLabel()) . '
                                                 </label>' : '') . '
                                  </div>';
                return parent::render();

            default:
                $label    = null;
                $mdb_init = '';
        }

        $render = $component->render();

        if ($component->hasOuterDiv()) {
            // Get attributes and properties for the outer div
            $outer      = $component->getOuterDiv();
            $class      = $outer->getClass();
            $attributes = $outer->getAttributesString();
        }

        $this->render .= match ($definition->getInputType()?->value) {
            default    => '  <div class="' . TemplatePage::getBottomMarginString() . Html::safe($definition->getSize() ? 'col-sm-' . $definition->getSize() : 'col') . ($definition->getVisible() ? '' : ' invisible') . ($definition->getDisplay() ? '' : ' d-none') . '">
                                 <div' . $mdb_init . ' class="form-outline' . ($group ? ' input-group' : null) . (isset($class) ? ' ' . $class : '') . '"' . (isset($attributes) ? ' ' . $attributes : '') . '>
                                     ' . $render . '
                                     <label class="form-label' . $label . '" for="' . Html::safe($definition->getColumn()) . '">
                                       ' . Html::safe($definition->getLabel()) . '
                                     </label>
                                 </div>
                             </div>',
//            ' . $this->renderTooltip($definition) . '
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