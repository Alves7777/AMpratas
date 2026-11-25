<?php

namespace App\Infrastructure\Mail;

use App\Domain\Contracts\OrderNotifierInterface;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use Throwable;

final class LaravelOrderNotifier implements OrderNotifierInterface
{
    public function placed(Order $order): void
    {
        $email = trim((string) $order->customer_email);

        if ($email === '') {
            return;
        }

        try {
            Mail::to($email)->send(new OrderPlacedMail($order));
        } catch (Throwable $exception) {
            report($exception);
        }
    }
}
