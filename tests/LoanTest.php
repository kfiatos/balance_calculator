<?php

use PHPUnit\Framework\TestCase;
use Loan\Loan;
use Loan\Enum\Currency;

class LoanTest extends TestCase
{
    /**
     * @expectedException InvalidArgumentException
     */
    public function test__constructWithZeroAmount()
    {
        $loan = new Loan(0, 50000, new DateTime('2018-01-01'), Currency::PLN);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function test__constructWithZeroCommission()
    {
        $loan = new Loan(170000, 0, new DateTime('2018-01-01'), Currency::PLN);
    }

    public function testGetStartDate()
    {
        $loan = new Loan(100000, 50000, new DateTime('2018-01-01'), Currency::PLN);
        $this->assertEquals('2018-01-01', $loan->getStartDate()->format('Y-m-d'));
    }

    public function testGetAmount()
    {
        $loan = new Loan(100000, 50000, new DateTime('2018-01-01'), Currency::PLN);
        $this->assertEquals(100000, $loan->getAmount());
        $this->assertTrue(is_int($loan->getAmount()));
    }

    public function testGetCommission()
    {
        $loan = new Loan(100000, 50000, new DateTime('2018-01-01'), Currency::PLN);
        $this->assertEquals(50000, $loan->getCommission());
        $this->assertTrue(is_int($loan->getCommission()));
    }
}
