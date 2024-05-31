<?php

declare(strict_types=1);

namespace Templates\AdminLte\Html\Modals;

use Phoundation\Web\Html\Components\Script;
use Phoundation\Web\Html\Components\Widgets\Modals\SignInModal;
use Phoundation\Web\Html\Enums\EnumDisplaySize;
use Phoundation\Web\Html\Layouts\Grid;
use Phoundation\Web\Html\Layouts\GridColumn;
use Phoundation\Web\Html\Layouts\GridRow;
use Phoundation\Web\Html\Template\TemplateRenderer;
use Phoundation\Web\Http\UrlBuilder;


/**
 * Class SignInModal
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\AdminLte
 */
class TemplateSignInModal extends TemplateRenderer
{
    /**
     * SignInModal class constructor
     */
    public function __construct(SignInModal $component)
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
        // Build the form
        $form = $this->component->getForm()->render();

        // Build the layout
        $layout = Grid::new()
            ->addRow(GridRow::new()
                ->addColumn(GridColumn::new()->setSize(EnumDisplaySize::three))
                ->addColumn(GridColumn::new()->setSize(EnumDisplaySize::six)->setContent($form))
                ->addColumn(GridColumn::new()->setSize(EnumDisplaySize::three))
            );

        // Set defaults
        $this->component
            ->setId('signinModal')
            ->setSize('lg')
            ->setTitle(tr('Sign in'))
            ->setContent($layout->render());

        // Render the sign in modal.
        return parent::render() . Script::new()
            ->setContent('
            $("form#form-sign-in").submit(function(e) {
                e.stopPropagation();

                $.post("' . UrlBuilder::getAjax('sign-in') . '", $(this).serialize())
                    .done(function (data, textStatus, jqXHR) {
                        $(".image-menu").replaceWith(data.html);
                        $("#signinModal").modal("hide");
                    });

                return false;
            })')->render();
    }
}