<?php

/**
 * Responsibility: Used to interact with the Brand table in the Database
 */

declare(strict_types=1);
/**
 * Layer between Brand Table in the DB, and Brand Controller
 */
interface BrandRepositoryInterface
{
    /**
     * Constructor Function
     *
     * @param Database $db
     */
    public function __construct(Database $db);

    /**
     * Reads a Brand from DB
     *
     * @param Brand $brand
     * @return ReturnType
     */
    public function create(Brand $brand): ReturnType;


    public function read(int $brand_id): ReturnType;

    public function update(array $names, array $values, int $brand_id): ReturnType;
}
