<?php

// Return Type for the Check Function in UserRepositoryClass. To be used by Login Function in UserClass
declare(strict_types=1);

class CheckReturnType extends ReturnType
{
    public bool $valid_user;
    public int $restaurant_id;

    public function __construct(bool $valid_user, bool $error, string $message, object $data)
    {

        parent::__construct($error, $message, $data);

        $this->valid_user = $valid_user;
    }
}
