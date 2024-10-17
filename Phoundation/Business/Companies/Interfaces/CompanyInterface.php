<?php

declare(strict_types=1);

namespace Plugins\Phoundation\Business\Companies\Interfaces;

use Plugins\Phoundation\Business\Companies\Branches\Interfaces\BranchesInterface;
use Plugins\Phoundation\Business\Companies\Departments\Interfaces\DepartmentsInterface;

interface CompanyInterface
{
    /**
     * Access company branches
     *
     * @return BranchesInterface
     */
    public function getBranches(): BranchesInterface;


    /**
     * Access company branches
     *
     * @return DepartmentsInterface
     */
    public function getDepartments(): DepartmentsInterface;


    /**
     * @inheritDoc
     */
    public function save(bool $force = false, bool $skip_validation = false, ?string $comments = null): static;
}
