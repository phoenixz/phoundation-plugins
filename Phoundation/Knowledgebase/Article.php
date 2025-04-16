<?php

/**
 * Class Article
 *
 *
 *
 * @see       DataEntry
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license   http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyarticle Copyarticle (c) 2025 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package   Plugins\Phoundation\Knowledgebase
 */


declare(strict_types=1);

namespace Plugins\Phoundation\Knowledgebase;

use Phoundation\Data\DataEntries\Exception\DataEntryNotExistsException;
use Phoundation\Data\DataEntries\Interfaces\IdentifierInterface;
use Phoundation\Data\DataEntries\Traits\TraitDataEntryBody;
use Phoundation\Data\DataEntries\Traits\TraitDataEntryCode;
use Phoundation\Data\Enums\EnumLoadParameters;
use Plugins\Phoundation\Knowledgebase\Exception\KnowledgebaseArticleNotExistsException;
use Phoundation\Data\DataEntries\DataEntry;
use Phoundation\Data\DataEntries\Definitions\DefinitionFactory;
use Phoundation\Data\DataEntries\Definitions\Interfaces\DefinitionsInterface;
use Phoundation\Data\DataEntries\Exception\DataEntryDeletedException;
use Phoundation\Data\DataEntries\Traits\TraitDataEntryNameLowercaseDash;
use Phoundation\Data\Validator\Interfaces\ValidatorInterface;
use Phoundation\Web\Html\Enums\EnumInputType;


class Article extends DataEntry
{
    use TraitDataEntryNameLowercaseDash;
    use TraitDataEntryBody;
    use TraitDataEntryCode;


    /**
     * Article class constructor
     *
     * @param IdentifierInterface|array|string|int|false|null $identifier
     */
    public function __construct(IdentifierInterface|array|string|int|false|null $identifier = false)
    {
        return parent::__construct(static::convertNameIdentifierToLowerCaseDash($identifier));
    }


    /**
     * Returns the table name used by this object
     *
     * @return string|null
     */
    public static function getTable(): ?string
    {
        return 'knowledgebase_articles';
    }


    /**
     * Returns the name of this DataEntry class
     *
     * @return string
     */
    public static function getEntryName(): string
    {
        return tr('Knowledgebase article');
    }


    /**
     * Returns the field that is unique for this object
     *
     * @return string|null
     */
    public static function getUniqueColumn(): ?string
    {
        return 'seo_name';
    }


    /**
     * Returns a Article object matching the specified identifier
     *
     * @note This method also accepts DataEntry objects, in which case it will simply return this object. This is to
     *       simplify "if this is not DataEntry object then this is new DataEntry object" into
     *       "PossibleDataEntryVariable is DataEntry::new(PossibleDataEntryVariable)"
     *
     * @param IdentifierInterface|array|string|int|null $identifier              Identifier for the DataEntry object to
     *                                                                           load. Can be specified with a
     *                                                                           [column => value] array, though also
     *                                                                           accepts an integer value which will convert
     *                                                                           to [id_column => integer_value] or a string
     *                                                                           value which will convert to
     *                                                                           [unique_column => string_value]]
     * @param EnumLoadParameters|null                   $on_load_null_identifier Specifies how this load method will handle
     *                                                                           the specified identifier being NULL.
     *                                                                           Options are: EnumLoadParameters::exception
     *                                                                           (Throws a
     *                                                                           DataEntryNoIdentifierSpecifiedException),
     *                                                                           EnumLoadParameters::null (will return NULL)
     *                                                                           or EnumLoadParameters::this (Will return
     *                                                                           the object as-is, without loading
     *                                                                           anything). Defaults to
     *                                                                           EnumLoadParameters::exception
     * @param EnumLoadParameters|null                   $on_load_not_exists      Specifies how this load method will handle
     *                                                                           the specified identifier not existing in
     *                                                                           the database. Options are:
     *                                                                           EnumLoadParameters::exception (Throws a
     *                                                                           DataEntryNotExistsException),
     *                                                                           EnumLoadParameters::null (will return NULL)
     *                                                                           or EnumLoadParameters::this (Will return
     *                                                                           the object as-is, without loading anything)
     *                                                                           Defaults to EnumLoadParameters::exception
     *
     * @return static|null
     */
    public function load(IdentifierInterface|array|string|int|null $identifier = null, ?EnumLoadParameters $on_load_null_identifier = null, ?EnumLoadParameters $on_load_not_exists = null): ?static
    {
        try {
            return parent::load($identifier, $on_load_null_identifier, $on_load_not_exists);

        } catch (DataEntryNotExistsException|DataEntryDeletedException $e) {
            throw new KnowledgebaseArticleNotExistsException($e);
        }
    }


    /**
     * Sets the available data keys for this entry
     *
     * @param DefinitionsInterface $definitions
     *
     * @return static
     */
    protected function setDefinitions(DefinitionsInterface $definitions): static
    {
        $definitions->add(DefinitionFactory::newName()
                                            ->setInputType(EnumInputType::name)
                                            ->setSize(12)
                                            ->setMaxlength(64)
                                            ->setHelpText(tr('The name for this article'))
                                            ->addValidationFunction(function (ValidatorInterface $validator) {
                                                $validator->isUnique();
                                            }))

                    ->add(DefinitionFactory::newCode()
                                           ->setRender(false)
                                           ->setSize(6))

                    ->add(DefinitionFactory::newSeoName())

                    ->add(DefinitionFactory::newBody());

        return $this;
    }
}
