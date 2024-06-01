<?php

declare(strict_types=1);

namespace Templates\Phoundation\Mdb\Html\Components\Tables;

use Phoundation\Web\Html\Components\Tables\HtmlTable;
use Phoundation\Web\Html\Layouts\GridRow;
use Phoundation\Web\Html\Template\TemplateRenderer;


/**
 * Class TemplateMdb TemplateHtmlDataTable
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\Mdb
 */
class TemplateHtmlDataTable extends TemplateRenderer
{
    /**
     * Table class constructor
     */
    public function __construct(HtmlTable $component)
    {
        $component->addClasses('table');
        parent::__construct($component);
    }


    /**
     * Renders and returns the HTML for this object
     *
     * @return string|null
     */
    public function render(): ?string
    {
        return GridRow::new()->addColumn(parent::render())->render();
    }
}
