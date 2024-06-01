<?php

declare(strict_types=1);

namespace Templates\Phoundation\AdminLte\Html\Components\Input;

use Phoundation\Exception\OutOfBoundsException;
use Phoundation\Web\Html\Components\Input\InputDateRange;


/**
 * Class TemplateInputDateRange
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\AdminLte
 */
class TemplateInputDateRange extends TemplateInput
{
    /**
     * InputText class constructor
     */
    public function __construct(InputDateRange $component)
    {
        $component->addClasses('form-control');
        parent::__construct($component);
    }


    /**
     * Render and return the HTML for this object
     *
     * @return string|null
     */
    public function render(): ?string
    {
        if (!$this->component->getName()) {
            throw new OutOfBoundsException(tr('Cannot render InputDateRange object, no HTML name attribute specified'));
        }

        // Ensure these two classes are always available
        $this->component->addClasses(['form-control', 'float-right']);

        $html = '   <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="far fa-calendar-alt"></i>
                            </span>
                        </div>
                        ' . parent::render() . '
                    </div>';

        // Add date range picker JS
        return $html;
    }
}
