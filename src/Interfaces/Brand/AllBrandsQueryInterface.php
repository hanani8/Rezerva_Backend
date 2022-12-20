<?php

/**
 * Responsibility: Used to fetch all brands, instead of a particular brand, from the Brands Table
 */

declare(strict_types=1);
/**
 * Serves the same purpose as BrandRepository, but operates on collection of brands instead one.
 */
interface AllBrandsQueryInterface
{
    /**
     * Constructor Function
     *
     * @param Database $db
     * @param Session $session
     */
    public function __construct(Database $db);

    /**
     * Fetch all Brands
     *
     * @return ReturnType
     */
    public function fetch(): ReturnType;
}
