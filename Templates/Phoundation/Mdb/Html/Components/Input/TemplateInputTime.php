<?php

/**
 * Class TemplateInputTime
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

use Phoundation\Web\Html\Components\Input\InputTime;
use Phoundation\Web\Html\Enums\EnumInputType;


class TemplateInputTime extends TemplateInputText
{
    /**
     * InputTime class constructor
     */
    public function __construct(InputTime $component)
    {
        $component->addClasses('form-control');
        $component->setInputType(EnumInputType::text);
        $component->getOuterDiv()
            ->addClasses('form-outline timepicker')
            ->getAttributes()
                ->add('', 'data-mdb-timepicker-init')
                ->add('', 'data-mdb-input-init');

        parent::__construct($component);
    }
}
