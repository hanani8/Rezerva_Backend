<?php

declare(strict_types=1);

class Reservation implements ReservationInterface
{
    private string $guest_name;
    private int $no_of_guests;
    private string $phone;
    private string $instructions;
    private string $reservation_time;
    private int $status;
    private int $reservation_id;
    private int $created_by;
    private string $created_at;
    private int $type;
    private string $table;

    public function __construct(
        string $guest_name,
        int $no_of_guests,
        string $phone,
        string $instructions,
        string $reservation_time,
        int $status,
        int $type,
        string $table,
        int $reservation_id = 0,
        int $created_by = 0,
        string $created_at = "",
    ) {
        $this->guest_name = $guest_name;
        $this->no_of_guests = $no_of_guests;
        $this->phone = $phone;
        $this->instructions = $instructions;
        $this->reservation_time = $reservation_time;
        $this->status = $status;
        $this->type = $type;
        $this->reservation_id = $reservation_id;
        $this->created_by = $created_by;
        $this->created_at = $created_at;
        $this->table = $table;
    }

    public function __get($name)
    {
        return $this->$name;
    }
}
