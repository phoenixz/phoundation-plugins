<?php

declare(strict_types=1);

namespace Templates\Mdb\Html\Components\Widgets\Cards;

use Phoundation\Enums\EnumOrientation;
use Phoundation\Web\Html\Html;
use Phoundation\Web\Html\Template\TemplateRenderer;


/**
 * Class TemplateCard
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\Mdb
 */
class TemplateCard extends TemplateRenderer
{
    /**
     * Card class constructor
     */
    public function __construct(\Phoundation\Web\Html\Components\Widgets\Cards\Card $component)
    {
        parent::__construct($component);
    }


    /**
     * @inheritDoc
     */
    public function render(): ?string
    {
        $tabs = $this->component->getTabsObject(false);

        if ($tabs and ($tabs->getOrientation() === EnumOrientation::top)) {
            $this->render = '   <div' . ($this->component->getId() ? ' id="' . $this->component->getId() . '"' : '') . ' class="card ' . ($this->component->getClass() ? $this->component->getClass() . ' ' : null) . ($this->component->getGradient() ? 'gradient-' . Html::safe($this->component->getGradient()) : '') . ($this->component->getMode()->value ? 'card-' . Html::safe($this->component->getMode()->value) : '') . ($this->component->getOutline() ? ' card-outline' : '') . ($this->component->getBackground() ? 'bg-' . Html::safe($this->component->getBackground()) : '') . ' card-tabs">
                                    <div class="card-header p-0 p-1 border-bottom-0">
                                        <ul class="nav nav-tabs" id="" role="tablist">';

            if ($this->component->getTitle()) {
                $this->render .= '          <li class="pt-2 px-3"><h3 class="card-title">' . $this->component->getTitle() . '</h3></li>';
            }

            // Render tabs
            $active = true;

            foreach ($tabs as $tab) {
                $this->render .= '          <li class="nav-item">
                                                <a class="nav-link' . ($active ? ' active' : '') . '" id="' . $tab->getId() . '-tab" data-toggle="pill" href="#' . $tab->getId() . '" role="tab" aria-controls="' . $tab->getId() . '" aria-selected="' . ($active ? 'true' : 'false') . '">' . $tab->getLabel() . '</a>
                                            </li>';
                $active = false;
            }

            // Render transition tabs to tab contents
            $this->render .= '          </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content">';

            // Render tab contents
            $active = true;

            foreach ($tabs as $tab) {
                $this->render .= '          <div class="tab-pane fade' . ($active ? ' active show' : '') . '" id="' . $tab->getId() . '" role="tabpanel" aria-labelledby="' . $tab->getId() . '-tab">
                                                ' . $tab->getContent() . '
                                            </div>';
                $active = false;
            }

            // Finish tab contents
            $this->render .= '          </div>
                                    </div>
                                </div>';

        } else {
            $this->render = '   <div' . ($this->component->getId() ? ' id="' . $this->component->getId() . '"' : '') . ' class="card ' . ($this->component->getClass() ? $this->component->getClass() . ' ' : null) . ($this->component->getGradient() ? 'gradient-' . Html::safe($this->component->getGradient()) : '') . ($this->component->getMode()->value ? 'card-' . Html::safe($this->component->getMode()->value) : '') . ($this->component->getOutline() ? ' card-outline' : '') . ($this->component->getBackground() ? 'bg-' . Html::safe($this->component->getBackground()) : '') . '">';

            if ($this->component->getReloadSwitch() or $this->component->getMaximizeSwitch() or $this->component->getCollapseSwitch() or $this->component->getCloseSwitch() or $this->component->getTitle() or $this->component->getHeaderContent()) {
                $this->render .= '  <div class="card-header">
                                        <h3 class="card-title">' . $this->component->getTitle() . '</h3>
                                        <div class="card-tools">
                                            ' . $this->component->getHeaderContent() . '
                                            ' . ($this->component->getReloadSwitch() ? '   <button type="button" class="btn btn-tool" data-card-widget="card-refresh" data-source="widgets.html" data-source-selector="#card-refresh-content" data-load-on-init="false">
                                                                                                 <i class="fas fa-sync-alt"></i>
                                                                                               </button>' : '') . '
                                            ' . ($this->component->getMaximizeSwitch() ? ' <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                                                                                 <i class="fas fa-expand"></i>
                                                                                               </button>' : '') . '
                                            ' . ($this->component->getCollapseSwitch() ? ' <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                                                                 <i class="fas fa-' . ($this->component->getCollapsed() ? 'plus' : 'minus') . '"></i>
                                                                                               </button>' : '') . '
                                            ' . ($this->component->getCloseSwitch() ? '    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                                                                                 <i class="fas fa-times"></i>
                                                                                               </button>' : '') . '                              
                                        </div>
                                    </div>';
            }

            $description   = $this->component->getDescription();
            $this->render .= '      <!-- /.card-header -->
                                    <div class="card-body">
                                        ' . ($description ? '<p class="card-description">' . $description . '</p>' : null) . '                                    
                                        ' . $this->component->getContent(). '
                                    </div>';

            if ($this->component->getButtons()) {
                $this->render .= '  <div class="card-footer">
                                        ' . $this->component->getButtons()->render() . '           
                                    </div>';
            }

            $this->render .= '  </div>';
        }

        return parent::render();
    }
}