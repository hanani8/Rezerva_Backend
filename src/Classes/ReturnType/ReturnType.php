<?php

declare(strict_types=1);

class ReturnType
{
    public bool $error;
    public string $message;
    public object $data;

    public function __construct(bool $error, string $message, object $data = new stdClass())
    {
        $this->error = $error;
        $this->message = $message;
        $this->data = $data;
    }

    public function __toString()
    {
        return "{" . "error:" . $this->error . "," . "message:" . $this->message . "}";
    }
}
