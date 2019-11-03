<?php

namespace App\Observers;

class PaymentObserver
{
    public function created(Payment $payment)
    {
        $payment->order->adjustBalance();
    }

    public function updated(Payment $payment)
    {
        $payment->order->adjustBalance();
    }

    public function deleted(Payment $payment)
    {
        $payment->order->adjustBalance();
    }
}
