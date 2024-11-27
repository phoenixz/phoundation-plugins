<?php

/**
 * Class Article
 *
 *
 *
 * @see       DataEntry
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license   http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyarticle Copyarticle (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package   Plugins\Phoundation\Knowledgebase
 */


declare(strict_types=1);

namespace Plugins\Phoundation\Knowledgebase;

use Phoundation\Data\DataEntry\Traits\TraitDataEntryBody;
use Phoundation\Data\DataEntry\Traits\TraitDataEntryCode;
use Plugins\Phoundation\Knowledgebase\Exception\KnowledgebaseArticleNotExistsException;
use Phoundation\Data\DataEntry\DataEntry;
use Phoundation\Data\DataEntry\Definitions\DefinitionFactory;
use Phoundation\Data\DataEntry\Definitions\Interfaces\DefinitionsInterface;
use Phoundation\Data\DataEntry\Exception\DataEntryDeletedException;
use Phoundation\Data\DataEntry\Exception\Interfaces\DataEntryNotExistsExceptionInterface;
use Phoundation\Data\DataEntry\Interfaces\DataEntryInterface;
use Phoundation\Data\DataEntry\Traits\TraitDataEntryNameLowercaseDash;
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
     * @param array|DataEntryInterface|string|int|null $identifier
     * @param bool|null                                $meta_enabled
     * @param bool                                     $init
     */
    public function __construct(array|DataEntryInterface|string|int|null $identifier = null, ?bool $meta_enabled = null, bool $init = true)
    {
        return parent::__construct(static::convertToLowerCaseDash($identifier), $meta_enabled, $init);
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
     * @param array|DataEntryInterface|string|int|null $identifier
     * @param bool                                     $meta_enabled
     * @param bool                                     $init
     * @param bool                                     $ignore_deleted
     *
     * @return Article
     */
    public static function load(array|DataEntryInterface|string|int|null $identifier, bool $meta_enabled = false, bool $init = true, bool $ignore_deleted = false): static
    {
        try {
            return parent::load(static::convertToLowerCaseDash($identifier), $meta_enabled, $init, $ignore_deleted);

        } catch (DataEntryNotExistsExceptionInterface|DataEntryDeletedException $e) {
            throw new KnowledgebaseArticleNotExistsException($e);
        }
    }


    /**
     * Sets the available data keys for this entry
     *
     * @param DefinitionsInterface $definitions
     */
    protected function setDefinitions(DefinitionsInterface $definitions): void
    {
        $definitions->add(DefinitionFactory::newName($this)
                                            ->setInputType(EnumInputType::name)
                                            ->setSize(12)
                                            ->setMaxlength(64)
                                            ->setHelpText(tr('The name for this article'))
                                            ->addValidationFunction(function (ValidatorInterface $validator) {
                                                $validator->isUnique();
                                            }))

                    ->add(DefinitionFactory::newCode($this)
                                           ->setRender(false)
                                           ->setSize(6))

                    ->add(DefinitionFactory::newSeoName($this))

                    ->add(DefinitionFactory::newBody($this));
    }
}
