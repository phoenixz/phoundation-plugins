<?php

declare(strict_types=1);

namespace Templates\Mdb\Html\Components\Input\Buttons\Buttons;

use Phoundation\Web\Html\Template\TemplateRenderer;


/**
 * Class TemplateButton
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\Mdb
 */
class TemplateButton extends TemplateRenderer
{
    /**
     * Button class constructor
     */
    public function __construct(\Phoundation\Web\Html\Components\Input\Buttons\InputButton $element)
    {
        parent::__construct($element);
    }
}