<?php
namespace Loan;

use Loan\Enum\Currency;

/**
 * Class Loan
 * @package Loan
 */
class Loan implements LoanInterface
{
    /**
     * @var int
     */
    private $amount;

    /**
     * @var int
     */
    private $commission;

    /**
     * @var \DateTime
     */
    private $startDate;

    /**
     * @var string
     */
    private $currency;

    /**
     * Loan constructor.
     * @param int $loanAmount
     * @param int $commission
     * @param \DateTime $startDate
     * @param string $currency
     */
    public function __construct(
        int $loanAmount,
        int $commission,
        \DateTime $startDate,
        string $currency = Currency::PLN
    ) {
        if ($loanAmount <= 0 || $commission <= 0) {
            throw new \InvalidArgumentException('Loan amount and commission has to be higher than zero');
        }

        $this->amount = $loanAmount;
        $this->commission = $commission;
        $this->startDate = $startDate;
        $this->currency = $currency;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @return int
     */
    public function getCommission(): int
    {
        return $this->commission;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate(): \DateTime
    {
        return $this->startDate;
    }
}