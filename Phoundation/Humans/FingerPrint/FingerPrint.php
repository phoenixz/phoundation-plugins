<?php

/**
 * Class FingerPrint
 *
 * This class manages finger print access using the Fprint class
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Plugins\Phoundation\Humans
 */


declare(strict_types=1);

namespace Plugins\Phoundation\Humans\FingerPrint;

use Phoundation\Accounts\Users\Interfaces\UserInterface;
use Phoundation\Data\DataEntry\DataEntry;
use Phoundation\Data\DataEntry\Definitions\Interfaces\DefinitionsInterface;
use Plugins\Phoundation\Humans\FingerPrint\Interfaces\FingerPrintsInterface;


class FingerPrint extends DataEntry
{
    public static function getTable(): ?string
    {
        return 'fingerprints';
    }


    public static function getEntryName(): string
    {
        return tr('Fingerprint');
    }


    public static function getUniqueColumn(): ?string
    {
        return 'users_id';
    }


    /**
     * Enrolls the specified user
     *
     * @param UserInterface $user
     * @return static
     */
    public static function enroll(UserInterface $user): static
    {
        return new static();
    }


    /**
     * Verifies the specified user
     *
     * @param UserInterface $user
     * @return static
     */
    public static function verify(UserInterface $user): static
    {
        return new static();
    }


    /**
     * Deletes fingerprints for the specified user
     *
     * @param string|null $comments
     * @return FingerPrint The number of removed fingerprints
     */
    public function delete(?string $comments = null): static
    {
        // TODO Remove the fingerprint from fprint

        return parent::delete($comments);
    }


    /**
     * Lists the available fingerprints for the specified user
     *
     * @param UserInterface $user
     * @return FingerPrintsInterface
     */
    public static function list(UserInterface $user): FingerPrintsInterface
    {
        return FingerPrints::new();
    }


    /**
     * @inheritDoc
     */
    protected function setDefinitions(DefinitionsInterface $definitions): static
    {
        // TODO: Implement initDefinitions() method.
        return $this;
    }
}
