<?php

/**
 * Class Articles
 *
 *
 *
 * @see       DataIterator
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license   http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyarticle Copyarticle (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package   Plugins\Phoundation\Knowledgebase
 */


declare(strict_types=1);

namespace Plugins\Phoundation\Knowledgebase;

use PDOStatement;
use Phoundation\Data\DataEntry\DataIterator;
use Phoundation\Data\Interfaces\IteratorInterface;
use Phoundation\Web\Html\Components\Input\InputSelect;
use Phoundation\Web\Html\Components\Input\Interfaces\InputSelectInterface;


class Articles extends DataIterator
{
    /**
     * Roles class constructor
     */
    public function __construct(IteratorInterface|array|string|PDOStatement|null $source = null)
    {
        $this->getQueryBuilder()->addSelect('`knowledgebase_articles`.`' . ($this->keys_are_unique_column ? 'seo_name' : 'id') . '` AS `id`, 
                                             `knowledgebase_articles`.`body`,
                                             CONCAT(UPPER(LEFT(`knowledgebase_articles`.`name`, 1)), SUBSTRING(`knowledgebase_articles`.`name`, 2)) AS `article`, 
                                             GROUP_CONCAT(CONCAT(UPPER(LEFT(`knowledgebase_articles`.`name`, 1)), SUBSTRING(`knowledgebase_articles`.`name`, 2)) SEPARATOR ", ") AS `roles`')

            ->addWhere('`knowledgebase_articles`.`status` IS NULL')
            ->addGroupBy('`knowledgebase_articles`.`name`')
            ->addOrderBy('`knowledgebase_articles`.`name`');

        parent::__construct($source);
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
     * Returns the field that is unique for this object
     *
     * @return string|null
     */
    public static function getUniqueColumn(): ?string
    {
        return 'seo_name';
    }


    /**
     * Returns the class for a single DataEntry in this Iterator object
     *
     * @return string|null
     */
    public static function getDefaultContentDataType(): ?string
    {
        return Article::class;
    }


    /**
     * Returns a "select" with the available articles
     *
     * @return InputSelect
     */
    public function getHtmlSelect(string $value_column = 'CONCAT(UPPER(LEFT(`name`, 1)), SUBSTRING(`name`, 2)) AS `name`', ?string $key_column = 'id', ?string $order = '`name` ASC', ?array $joins = null, ?array $filters = ['status' => null]): InputSelectInterface
    {
        return parent::getHtmlSelect($value_column, $key_column, $order, $joins, $filters)
            ->setName('articles_id')
            ->setNotSelectedLabel(tr('Select a article'))
            ->setComponentEmptyLabel(tr('No articles available'));
    }
}
