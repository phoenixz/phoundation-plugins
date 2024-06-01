<?php

/**
 * Class TemplateInputRadio
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\Mdb
 */

declare(strict_types=1);

namespace Templates\Phoundation\Mdb\Html\Components\Input;

use Phoundation\Web\Html\Components\Input\InputRadio;


class TemplateInputRadio extends TemplateInput
{
    /**
     * TemplateInputRadio class constructor
     */
    public function __construct(InputRadio $component)
    {
        parent::__construct($component);
        $component->getClasses()->removeKeys('form-control')->add(true, 'form-check-input');
    }
}
