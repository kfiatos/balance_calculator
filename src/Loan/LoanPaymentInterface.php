<?php

namespace Loan;

/**
 * Interface LoanPaymentInterface
 * @package Loan
 */
interface LoanPaymentInterface
{
    /**
     * @return int
     */
    public function getPaidAmount(): int;

    /**
     * @return \DateTime
     */
    public function getPaidDate(): \DateTime;
}