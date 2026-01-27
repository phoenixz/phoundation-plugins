<?php

/**
 * Class Editor
 *
 *
 *
 * @author    Sven Olaf Oostenbrink <sven@medinet.ca>
 * @license   http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2025 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package   Phoundation\Plugins\Editors
 */


declare(strict_types=1);

namespace Plugins\Phoundation\Editors;


abstract class Editor
{
    public function install(): static
    {

    }


    public function render(): ?string
    {

    }
}
