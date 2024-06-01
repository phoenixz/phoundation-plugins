<?php

/**
 * Class TemplateModal
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\Mdb
 */

declare(strict_types=1);

namespace Templates\Phoundation\Mdb\Html\Components\Widgets\Modals;

use Phoundation\Web\Html\Components\Widgets\Modals\Modal;
use Phoundation\Web\Html\Template\TemplateRenderer;


class TemplateModal extends TemplateRenderer
{
    /**
     * Modal class constructor
     */
    public function __construct(Modal $component)
    {
        parent::__construct($component);
    }


    /**
     * Render the HTML for this sign-in modal
     *
     * @return string|null
     */
    public function render(): ?string
    {
        // Get the render component to use it directly
        $component = $this->component;

//        $form   = $component->getForm()->render();
//
//        // Build the layout
//        $layout = Grid::new()
//            ->addRow(GridRow::new()
//                ->addColumn(GridColumn::new()->setSize(EnumDisplaySize::three))
//                ->addColumn(GridColumn::new()->setSize(EnumDisplaySize::six)->setContent($form))
//                ->addColumn(GridColumn::new()->setSize(EnumDisplaySize::three))
//            );
//
//        // Set defaults
//        $component->setContent($layout->render());

        // Render the modal.

//        return '<div class="modal fade" tabindex="-1" id="' . $component->getId() . '" style="position: static; display: block; height: auto;" role="dialog">
//                  <div class="modal-dialog modal-' . $component->getSize() . '">
//                    <div class="modal-content">
//                      <div class="modal-header">
//                        <h5 class="modal-title">Modal title</h5>
//                        <button type="button" class="btn-close" data-mdb-ripple-init="" data-mdb-dismiss="modal" aria-label="Close" style=""></button>
//                      </div>
//                      <div class="modal-body">
//                        <p>Modal body text goes here.</p>
//                      </div>
//                      <div class="modal-footer">
//                        <button type="button" class="btn btn-secondary" data-mdb-ripple-init="" data-mdb-dismiss="modal">
//                          Close
//                        </button>
//                        <button type="button" class="btn btn-primary" data-mdb-ripple-init="">Save changes</button>
//                      </div>
//                    </div>
//                  </div>
//                </div>';

        return '<div class="modal fade" id="modal-' . $component->getId() . '" style="display: none;" aria-hidden="true" role="dialog">
                    <div class="modal-dialog modal-' . $component->getSize() . '">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">' . ($component->getTitle() ?? tr('No title specified')) . '</h4>
                                <button type="button" class="btn-close" data-mdb-ripple-init="" data-mdb-dismiss="modal" aria-label="Close" style=""></button>
                            </div>
                            <div class="modal-body">
                                ' . $component->getContent() . '
                            </div>
                            <div class="modal-footer justify-content-between buttons">
                                ' . $component->getButtons()->render() . '
                            </div>
                        </div>
                    </div>
                </div>';
    }
}