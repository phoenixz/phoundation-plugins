<?php

declare(strict_types=1);

namespace Templates\Phoundation\AdminLte\Html\Components\Widgets\Modals;

use Phoundation\Web\Html\Template\TemplateRenderer;


/**
 * Class TemplateModal
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\AdminLte
 */
class TemplateModal extends TemplateRenderer
{
    /**
     * Modal class constructor
     */
    public function __construct(\Phoundation\Web\Html\Components\Widgets\Modals\Modal $component)
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
        // Get the render object to use it directly
        $object = $this->component;

//        $form   = $object->getForm()->render();
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
//        $object->setContent($layout->render());

        // Render the modal.
        return '<div class="modal fade" id="' . $object->getId() . '" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-' . $object->getSize() . '">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">:TITLE</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                :BODY_GRID
                            </div>
                            <div class="modal-footer justify-content-between buttons">
                                <button type="button" class="btn btn-default" data-dismiss="modal">' . tr('Close') . '</button>
                                <button type="button" class="btn btn-primary">' . tr('See more') . '</button>
                            </div>
                        </div>
                    </div>
                </div>';
    }
}