<?php

/**
 * Responsibility: Used to fetch all reservations, instead of a particular reservation, from the Reservations Table
 */

declare(strict_types=1);
/**
 * Serves the same purpose as ReservationRepository, but operates on collection of reservations instead one.
 */
interface AllReservationsQueryInterface
{
    /**
     * Constructor Function
     *
     * @param Database $db
     * @param Session $session
     */
    public function __construct(Database $db, Session $session);

    /**
     * Fetch all Reservations
     *
     * @return ReturnType
     */
    public function fetch(): ReturnType;

    /**
     * Fetch all Reservations from a particular date.
     *
     * @param string $date
     * @return ReturnType
     */
    public function fetchWithDate(string $date): ReturnType;
}
