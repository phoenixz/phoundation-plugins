<?php

declare(strict_types=1);

namespace Templates\Phoundation\Mdb\Html\Components\Input;

use Phoundation\Web\Html\Components\Input\InputSelect;


/**
 * Class TemplateInputSelect2
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\Mdb
 */
class TemplateInputSelect2 extends TemplateInputSelect
{
    /**
     * Select class constructor
     */
    public function __construct(InputSelect $component)
    {
        $component->addClasses('form-control');
        parent::__construct($component);
    }
}
