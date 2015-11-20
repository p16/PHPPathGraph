<?php

namespace Sort\DTO;

class Edge
{
    private $from;
    private $to;
    private $description;

    public function __construct($from, $to, $description)
    {
        $this->from = $from;
        $this->to = $to;
        $this->description = $description;
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function getTo()
    {
        return $this->to;
    }

    public function getDescription()
    {
        return $this->description;
    }
}
