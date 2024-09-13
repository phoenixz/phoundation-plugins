<?php

/**
 * Enum EnumFinger
 *
 *
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license   http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package   Plugins\Phoundation\FingerPrint
 */


declare(strict_types=1);

namespace Plugins\Phoundation\FingerPrint\Enums;


/**
 * Enum EnumFinger
 *
 * Contains a list of all possible fingers for the FingerPrint class
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Phoundation\Processes
 */
enum EnumFinger: string
{
    case left_thumb          = 'left-thumb';
    case left_index_finger   = 'left-index-finger';
    case left_middle_finger  = 'left-middle-finger';
    case left_ring_finger    = 'left-ring-finger';
    case left_little_finger  = 'left-little-finger';
    case right_thumb         = 'right-thumb';
    case right_index_finger  = 'right-index-finger';
    case right_middle_finger = 'right-middle-finger';
    case right_ring_finger   = 'right-ring-finger';
    case right_little_finger ='right-little-finger';
}
