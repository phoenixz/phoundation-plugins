<?php

 namespace Plugins\Phoundation\MultiFactorAuthentication\Interfaces;

use RobThree\Auth\TwoFactorAuthException;

interface MultiFactorAuthenticationInterface
{
    /**
     * Returns true if the MFA test for this user passes
     *
     * @return bool
     */
    public function pass(): bool;

    /**
     * @param string   $code
     * @param bool     $test
     * @param int|null $time
     *
     * @return static
     */
    public function verify(string $code, bool $test, ?int $discrepancy = null, ?int $time = null): static;

    /**
     * Renders and returns the multi-factor verify section
     *
     * @return string|null
     */
    public function renderCreate(): ?string;

    /**
     * Renders and returns the multi-factor verify section
     *
     * @return string|null
     */
    public function renderVerify(): ?string;
}
