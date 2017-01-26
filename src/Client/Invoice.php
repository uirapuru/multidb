<?php
namespace Client;

use DateTime;

class Invoice
{
    /** @var string */
    protected $number;

    /** @var string */
    protected $company;

    /** @var DateTime */
    protected $createdAt;

    /**
     * Invoice constructor.
     * @param string $number
     * @param string $company
     * @param DateTime $createdAt
     */
    public function __construct($number, $company, DateTime $createdAt)
    {
        $this->number = $number;
        $this->company = $company;
        $this->createdAt = $createdAt;
    }

    /**
     * @param $array
     * @return Invoice
     */
    public static function fromArray($array)
    {
        return new self($array['number'], $array['company'], $array['createdAt']);
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
