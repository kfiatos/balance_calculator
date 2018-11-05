<?php
namespace Loan;

/**
 * Interface LoanInterface
 * @package Loan
 */
interface LoanInterface
{
    /**
     * @return int
     */
    public function getAmount(): int;

    /**
     * @return int
     */
    public function getCommission(): int;

    /**
     * @return \DateTime
     */
    public function getStartDate(): \DateTime;
}