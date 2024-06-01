<?php

declare(strict_types=1);

namespace Templates\Phoundation\Mdb\Html\Components;

use Phoundation\Web\Html\Components\A;
use Phoundation\Web\Html\Template\TemplateRenderer;


/**
 * Class TemplateA
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\Mdb
 */
class TemplateA extends TemplateRenderer
{
    /**
     * Icons class constructor
     */
    public function __construct(A $component)
    {
        parent::__construct($component);
    }


    /**
     * Render the a HTML
     *
     * @note This render skips the parent Element class rendering for speed and simplicity
     * @return string|null
     */
    public function render(): ?string
    {
        $this->component->addClasses('nav-link');
        return parent::render();
    }
}