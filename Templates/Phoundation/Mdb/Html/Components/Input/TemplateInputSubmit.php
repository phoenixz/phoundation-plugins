<?php

declare(strict_types=1);

namespace Templates\Phoundation\Mdb\Html\Components\Input;

use Phoundation\Web\Html\Components\Input\InputSubmit;


/**
 * Class TemplateInputSubmit
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\Mdb
 */
class TemplateInputSubmit extends TemplateInput
{
    /**
     * InputSubmit class constructor
     */
    public function __construct(InputSubmit $component)
    {
        $component->addClasses('form-control');
        parent::__construct($component);
    }
}