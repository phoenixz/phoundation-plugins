<?php

/**
 * Class Editor
 *
 *
 *
 * @author    Sven Olaf Oostenbrink <sven@medinet.ca>
 * @license   This plugin is developed by Medinet and may only be used by others with explicit written authorization
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
