<?php
declare(strict_types=1);

namespace Loan;

/**
 * Class LoanPayment
 * @package Loan
 */
class LoanPayment implements LoanPaymentInterface
{
    /**
     * @var int
     */
    private $paidAmount;

    /**
     * @var \DateTime
     */
    private $paidDate;

    /**
     * LoanPayment constructor.
     * @param int $paidAmount
     * @param \DateTime $paidDate
     */
    public function __construct($paidAmount, \DateTime $paidDate)
    {
        if ($paidAmount <= 0) {
            throw new \InvalidArgumentException('Paid amount has to be greater than 0');
        }

        $this->paidAmount = $paidAmount;
        $this->paidDate = $paidDate;
    }

    /**
     * @return int
     */
    public function getPaidAmount(): int
    {
        return $this->paidAmount;
    }

    /**
     * @return \DateTime
     */
    public function getPaidDate(): \DateTime
    {
        return $this->paidDate;
    }
}