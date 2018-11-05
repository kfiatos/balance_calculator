<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Loan\Loan;
use Loan\LoanBalanceCalculator;
use Loan\LoanPayment;

/**
 * Class SimpleLoanTest
 */
class LoanBalanceCalculatorTest extends TestCase
{
    public function testGetStartingLoanBalance(): void
    {
        $loanBalanceCalculator = new LoanBalanceCalculator(
            new Loan(170000, 46665, new DateTime('2018-11-01')),
            45
        );

        $this->assertEquals(216665, $loanBalanceCalculator->getStartingBalance());
    }

    public function testCalculateProperBalanceAfterAddingOnePayment()
    {
        $loanBalanceCalculator = new LoanBalanceCalculator(
            new Loan(170000, 46665, new DateTime('2018-11-01')),
            45
        );

        $this->assertEquals(216755, $loanBalanceCalculator->getBalanceForDate(new DateTime('2018-11-03')));

        $loanBalanceCalculator->addLoanPayment(new LoanPayment(100000, new DateTime('2018-11-04')));
        $this->assertEquals(116800, $loanBalanceCalculator->getBalanceForDate(new DateTime('2018-11-04')));
    }

    public function testCalculateProperBalanceAfterAddingTwoOrMorePayments()
    {
        $loanBalanceCalculator = new LoanBalanceCalculator(
            new Loan(170000, 46665, new DateTime('2018-11-01')),
            45
        );

        $this->assertEquals(216755, $loanBalanceCalculator->getBalanceForDate(new DateTime('2018-11-03')));

        $loanBalanceCalculator->addLoanPayment(new LoanPayment(100000, new DateTime('2018-11-04')));
        $this->assertEquals(116800, $loanBalanceCalculator->getBalanceForDate(new DateTime('2018-11-04')));

        $loanBalanceCalculator->addLoanPayment(new LoanPayment(50045, new DateTime('2018-11-05')));
        $this->assertEquals(66800, $loanBalanceCalculator->getBalanceForDate(new DateTime('2018-11-05')));

        $loanBalanceCalculator->addLoanPayment(new LoanPayment(50000, new DateTime('2018-11-09')));
        $this->assertEquals(16980, $loanBalanceCalculator->getBalanceForDate(new DateTime('2018-11-09')));

        $loanBalanceCalculator->addLoanPayment(new LoanPayment(50000, new DateTime('2018-11-12')));
        $this->assertEquals(16980, $loanBalanceCalculator->getBalanceForDate(new DateTime('2018-11-09')));
    }

    public function testCalculateProperBalanceWithoutLastPayment()
    {
        $loanBalanceCalculator = new LoanBalanceCalculator(
            new Loan(170000, 46665, new DateTime('2018-11-01')),
            45
        );

        $loanBalanceCalculator->addLoanPayment(new LoanPayment(100000, new DateTime('2018-11-12')));
        $this->assertEquals(217025, $loanBalanceCalculator->getBalanceForDate(new DateTime('2018-11-09')));
    }

    public function testReturnZeroForWrongDate()
    {
        $loanBalanceCalculator = new LoanBalanceCalculator(
            new Loan(170000, 46665, new DateTime('2018-11-01')),
            45
        );

        $this->assertEquals(0, $loanBalanceCalculator->getBalanceForDate(new DateTime('2018-10-30')));
    }

    public function testOutputCalculatedBalanceAsFormattedString()
    {
        $loanBalanceCalculator = new LoanBalanceCalculator(
            new Loan(170000, 46665, new DateTime('2018-11-01')),
            45
        );
        $this->assertEquals(
            '2,167.55',
            number_format(
                (float)bcdiv((string)$loanBalanceCalculator->getBalanceForDate(new DateTime('2018-11-03')), '100', 2),
                2
            )
        );
    }


}