<?php

/**
 * Responsibility: Used to interact with the Reservations table in the Database
 */

declare(strict_types=1);
/**
 * Layer between Reservations Table in the DB, and Reservation Controller, Other Classes
 */
interface ReservationRepositoryInterface
{
    /**
     * Constructor Function
     *
     * @param Database $db
     * @param Session $session
     */
    public function __construct(Database $db, Session $session);

    /**
     * Creates a Reservation in DB
     *
     * @param Reservation $reservation
     * @return ReturnType
     */
    public function create(Reservation $reservation): ReturnType;

    /**
     * Reads a Reservation from DB
     *
     * @param integer $reservation_id
     * @return ReturnType
     */
    public function read(int $reservation_id):ReturnType;

    /**
     * Updates a Reservation from DB
     *
     * @param Reservation $reservation
     * @param integer $reservation_id
     * @return ReturnType
     */
    public function update(Reservation $reservation, int $reservation_id):ReturnType;

    /**
     * Updates only specific cols of a Reservation from DB
     * 
     * @param Array $names
     * @param Array $values
     */

     public function newUpdate(array $names, array $values, int $reservation_id): ReturnType;
}
