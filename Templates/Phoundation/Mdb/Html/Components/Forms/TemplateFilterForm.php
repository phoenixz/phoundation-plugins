<?php

declare(strict_types=1);

namespace Templates\Mdb\Html\Components\Forms;

use Templates\Mdb\Html\Components\DataEntryForm;


/**
 * Class FilterForm
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\Mdb
 */
class TemplateFilterForm extends DataEntryForm
{
    /**
     * FilterForm class constructor
     */
    public function __construct(\Phoundation\Web\Html\Components\Forms\FilterForm $element)
    {
        parent::__construct($element);
    }
}