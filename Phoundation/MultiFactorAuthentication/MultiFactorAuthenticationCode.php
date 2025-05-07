<?php

/**
 * Class MultiFactorAuthenticationCode
 *
 *
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license   http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2025 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package   Plugins\Phoundation\MultiFactorAuthentication
 */


declare(strict_types=1);

namespace Plugins\Phoundation\MultiFactorAuthentication;

use Phoundation\Data\DataEntries\DataEntry;
use Phoundation\Data\DataEntries\Definitions\DefinitionFactory;
use Phoundation\Data\DataEntries\Definitions\Interfaces\DefinitionsInterface;
use Phoundation\Data\DataEntries\Traits\TraitDataEntryCode;
use Phoundation\Data\DataEntries\Traits\TraitDataEntryIpAddress;
use Phoundation\Data\DataEntries\Traits\TraitDataEntrySessionCode;
use Phoundation\Data\DataEntries\Traits\TraitDataEntryUser;


class MultiFactorAuthenticationCode extends DataEntry
{
    use TraitDataEntryUser;
    use TraitDataEntryCode;
    use TraitDataEntrySessionCode;
    use TraitDataEntryIpAddress;


    /**
     * Returns the table name used by this object
     *
     * @return string|null
     */
    public static function getTable(): ?string
    {
        return 'multifactorauthentication_codes';
    }


    /**
     * Returns the name of this DataEntry class
     *
     * @return string
     */
    public static function getEntryName(): string
    {
        return tr('Multi factor authentication code');
    }


    /**
     * Returns the field that is unique for this object
     *
     * @return string|null
     */
    public static function getUniqueColumn(): ?string
    {
        return null;
    }


    /**
     * Sets the available data keys for this entry
     *
     * @param DefinitionsInterface $o_definitions
     *
     * @return static
     */
    protected function setDefinitionsObject(DefinitionsInterface $o_definitions): static
    {
        $o_definitions->add(DefinitionFactory::newUsersId())

                      ->add(DefinitionFactory::newCode('code'))

                      ->add(DefinitionFactory::newCode('session_code'))

                      ->add(DefinitionFactory::newIpAddress());

        return $this;
    }
}