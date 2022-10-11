<?php

/**
 * Responsibility: Represents a Reservation Object.
 */

declare(strict_types=1);

interface ReservationInterface
{
    public function __construct(
        string $guest_name,
        int $no_of_guests,
        string $phone,
        string $instructions,
        string $reservation_time,
        int $status,
        int $type,
        int $reservation_id = 0,
        int $created_by = 0,
        string $created_at = ""
    );
}
