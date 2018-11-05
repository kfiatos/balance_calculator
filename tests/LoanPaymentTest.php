<?php

use PHPUnit\Framework\TestCase;
use Loan\LoanPayment;

class LoanPaymentTest extends TestCase
{
    /**
    * @expectedException InvalidArgumentException
    */
    public function test__construct()
    {
        $loanPayment = new LoanPayment(-1, new DateTime('2018-11-01'));
    }

    public function testGetPaidDate()
    {
        $loanPayment = new LoanPayment(100000, new DateTime('2018-11-01'));
        $this->assertEquals('2018-11-01', $loanPayment->getPaidDate()->format('Y-m-d'));
    }

    public function testGetPaidAmount()
    {
        $loanPayment = new LoanPayment(12345, new DateTime('2018-11-01'));
        $this->assertEquals(12345, $loanPayment->getPaidAmount());
        $this->assertTrue(is_int($loanPayment->getPaidAmount()));
    }
}
