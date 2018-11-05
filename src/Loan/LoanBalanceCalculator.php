<?php
declare(strict_types=1);

namespace Loan;

/**
 * Class LoanBalanceCalculator
 * @package Loan
 */
class LoanBalanceCalculator
{
    /**
     * @var LoanInterface
     */
    private $loan;

    /**
     * @var int
     */
    private $perDayInterestsRate;

    /**
     * @var array
     */
    private $loanPayments = [];

    /**
     * @var int
     */
    private $currentCommission = 0;

    /**
     * @var int
     */
    private $currentInterest = 0;

    /**
     * @var int
     */
    private $currentBalance = 0;

    /**
     * LoanService constructor.
     * @param LoanInterface $loan
     * @param int $perDayInterestsRate
     */
    public function __construct(LoanInterface $loan, int $perDayInterestsRate)
    {
        $this->loan = $loan;
        $this->perDayInterestsRate = $perDayInterestsRate;
    }

    /**
     * @param LoanPaymentInterface $loanPayment
     */
    public function addLoanPayment(LoanPaymentInterface $loanPayment): void
    {
        $this->loanPayments[] = $loanPayment;
    }

    /**
     * @return int
     */
    public function getStartingBalance(): int
    {
        return (int)bcadd((string)$this->loan->getAmount(), (string)$this->loan->getCommission());
    }

    /**
     * @param \DateTime $date
     * @return int
     */
    public function getBalanceForDate(\DateTime $date): int
    {
        if ($date < $this->loan->getStartDate()) {
            //Depends on specification we can throw exception or return 0. In this case I return 0.
            return 0;
        }

        $this->currentCommission = $this->loan->getCommission();
        $this->currentBalance = $this->loan->getAmount();

        $numberOfDaysFromLoanStart = $date->diff($this->loan->getStartDate())->days;
        $this->currentInterest = (int)bcmul((string)$numberOfDaysFromLoanStart, (string)$this->perDayInterestsRate, 2);

        foreach ($this->loanPayments as $payment) {
            $this->updateBalanceByPayment($payment, $date);
        }

        $balance = bcadd(bcadd((string)$this->currentBalance, (string)$this->currentCommission, 2),
            (string)$this->currentInterest, 2);
        return (int)$balance;
    }

    /**
     * @param LoanPayment $loanPayment
     * @param \DateTime $date
     */
    private function updateBalanceByPayment(LoanPayment $loanPayment, \DateTime $date): void
    {
        if ($loanPayment->getPaidDate() > $date) {
            return;
        }

        //Subtract remaining interests from payment
        if ($loanPayment->getPaidAmount() >= $this->currentInterest) {
            $paymentAmountAfterCoveringInterests = $loanPayment->getPaidAmount() - $this->currentInterest;
            $this->currentInterest = 0;
        } else {
            $this->currentInterest = $this->currentInterest - $loanPayment->getPaidAmount();
            return;
        }

        //Subtract remaining commission from payment
        if ($paymentAmountAfterCoveringInterests >= $this->loan->getCommission()) {
            $paymentAmountAfterCoveringCommissionAndInterests = $paymentAmountAfterCoveringInterests - $this->currentCommission;
            $this->currentCommission = 0;
        } else {
            $this->currentCommission = $this->currentCommission - $paymentAmountAfterCoveringInterests;
            return;
        }

        //Subtract payment from remaining amount
        if ($paymentAmountAfterCoveringCommissionAndInterests >= $this->currentBalance) {
            $this->currentBalance = 0;
        } else {
            $this->currentBalance = $this->currentBalance - $paymentAmountAfterCoveringCommissionAndInterests;
        }
    }
}