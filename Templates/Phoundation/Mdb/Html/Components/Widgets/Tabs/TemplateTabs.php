<?php
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

declare(strict_types=1);

namespace Templates\Phoundation\Mdb\Html\Components\Widgets\Tabs;

use Phoundation\Enums\EnumOrientation;
use Phoundation\Exception\UnderConstructionException;
use Phoundation\Web\Html\Components\Widgets\Tabs\Tabs;
use Phoundation\Web\Html\Template\TemplateRenderer;


class TemplateTabs extends TemplateRenderer
{
    /**
     * Card class constructor
     */
    public function __construct(Tabs $component)
    {
        parent::__construct($component);
    }


    /**
     * @inheritDoc
     */
    public function render(): ?string
    {
        $buttons              = null;
        $tabs                 = $this->component;
        $content_display_size = $tabs->getContentDisplaySize()->value;
        $tab_display_size     = 12 - $content_display_size;

        if ($tabs->getButtons()->getCount()) {
            $buttons = '<div class="modal-footer justify-content-between buttons">
                            ' . $tabs->getButtons()->render() . '
                        </div>';
        }

        switch ($tabs->getOrientation()) {
            case EnumOrientation::top:
                $this->render .= '  <div class="row">
                                        <ul class="nav nav-tabs mb-3" role="tablist">';

                // Render the tabs
                $active = true;

                foreach ($tabs as $tab) {
                    $this->render .= '      <li class="nav-item" role="presentation">
                                                <a data-mdb-tab-init class="nav-link ' . ($active ? ' active' : '') . $tab->getClass(' ') . '" id="' . $tab->getId() . '-tab" href="#' . $tab->getId() . '" role="tab" aria-controls="' . $tab->getId() . '" aria-selected="' . ($active ? 'true' : 'false') . '">
                                                    ' . $tab->getLabel() . '
                                                </a>
                                            </li>';

                    $active = false;
                }

                // Render the change to tabs / contents
                $this->render .= '      </ul>
                                        <div class="tab-content" id="ex-with-icons-content">';

                // Render the tab contents
                $active = true;

                foreach ($tabs as $tab) {
                    $this->render .= '      <div class="tab-pane fade' . ($active ? ' active show' : '') . '" id="' . $tab->getId() . '" role="tabpanel" aria-labelledby="' . $tab->getId() . '-tab">
                                                ' . $tab->getContent() . '
                                            </div>';

                    $active = false;
                }

                $this->render .= '      </div>
                                        ' . $buttons . '
                                    </div>';
                break;

            case EnumOrientation::left:
                $this->render .= '  <div class="row w-100">
                                        <div class="col-' . $tab_display_size . ' col-sm-' . $tab_display_size . '">
                                            <div class="nav flex-column nav-tabs text-center" id="v-tabs-tab" role="tablist" aria-orientation="vertical">';

                // Render the tabs
                $active = true;

                foreach ($tabs as $tab) {
                    $this->render .= '          <a data-mdb-tab-init class="nav-link' . $tab->getClass(' ') . ($active ? ' active' : '') . '" id="' . $tab->getId() . '-tab" href="#' . $tab->getId() . '" role="tab" aria-controls="' . $tab->getId() . '" aria-selected="' . ($active ? 'true' : 'false') . '">
                                                    ' . $tab->getLabel() . '
                                                </a>';
                    $active = false;
                }

                // Render the change to tabs / contents
                $this->render .= '          </div>
                                        </div>
                                        <div class="col-' . $content_display_size . ' col-sm-' . $content_display_size . '">
                                            <div class="tab-content" id="v-tabs-tabContent">';

                // Render the tab contents
                $active = true;

                foreach ($tabs as $tab) {
                    $this->render .= '          <div class="tab-pane fade' . ($active ? ' active show' : '') . '" id="' . $tab->getId() . '" role="tabpanel" aria-labelledby="' . $tab->getId() . '-tab">
                                                  ' . $tab->getContent() . '
                                                </div>';

                    $active = false;
                }

                $this->render .= '          </div>
                                        </div>
                                        ' . $buttons . '
                                    </div>';
                break;

            case EnumOrientation::right:
                throw new UnderConstructionException(tr('right orientation for tabs with MDB template is still under construction!'));
                $this->render .= '  <div class="row">
                                        <div class="col-' . $content_display_size . ' col-sm-' . $content_display_size . '">
                                            <div class="tab-content" id="vert-tabs-tabContent">';

                // Render the tab contents
                $active = true;

                foreach ($tabs as $tab) {
                    $this->render .= '          <div class="tab-pane text-left fade' . ($active ? ' active show' : '') . '" id="' . $tab->getId() . '" role="tabpanel" aria-labelledby="' . $tab->getId() . '-tab">
                                                    ' . $tab->getContent() . '
                                                </div>';

                    $active = false;
                }

                $this->render .= '          </div>
                                        </div>
                                        <div class="col-' . $tab_display_size . ' col-sm-' . $tab_display_size . '">
                                            <div class="nav flex-column nav-tabs nav-tabs-right h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">';

                // Render the tabs
                $active = true;

                foreach ($tabs as $tab) {
                    $this->render .= '          <a class="nav-link' . $tab->getClass(' ') . ($active ? ' active' : '') . '" id="' . $tab->getId() . '-tab" data-toggle="pill" href="#' . $tab->getId() . '" role="tab" aria-controls="' . $tab->getId() . '" aria-selected="' . ($active ? 'true' : 'false') . '">
                                                    ' . $tab->getLabel() . '
                                                </a>';
                    $active = false;
                }

                // Render the change to tabs / contents
                $this->render .= '          </div>
                                        </div>
                                        ' . $buttons . '
                                    </div>';
                break;

            case EnumOrientation::bottom:
                throw new UnderConstructionException(tr('bottom orientation for tabs is still under construction!'));
        }

        return parent::render();
    }
}