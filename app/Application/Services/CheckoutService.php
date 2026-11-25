<?php

namespace App\Application\Services;

use App\Application\DTOs\CartViewData;
use App\Application\DTOs\CheckoutCustomerData;
use App\Application\DTOs\PaymentIntentData;
use App\Application\DTOs\PlaceOrderResult;
use App\Domain\Contracts\CartStoreInterface;
use App\Domain\Contracts\OrderNotifierInterface;
use App\Domain\Contracts\PaymentLookupInterface;
use App\Domain\Contracts\Repositories\OrderRepositoryInterface;
use App\Domain\Enums\PaymentStatus;
use App\Domain\Exceptions\EmptyCartException;
use App\Domain\Exceptions\PaymentGatewayException;
use App\Infrastructure\Payment\PaymentGatewayManager;
use App\Models\Order;

final class CheckoutService
{
    public function __construct(
        private readonly CartStoreInterface $cart,
        private readonly OrderRepositoryInterface $orders,
        private readonly PaymentGatewayManager $payments,
        private readonly PaymentLookupInterface $paymentsLookup,
        private readonly OrderNotifierInterface $ordersNotifier,
    ) {}

    public function checkoutView(): CartViewData
    {
        $this->guardAgainstEmptyCart();

        return new CartViewData(
            items: $this->cart->items(),
            subtotal: $this->cart->subtotal(),
        );
    }

    public function placeOrder(CheckoutCustomerData $customer): PlaceOrderResult
    {
        $this->guardAgainstEmptyCart();

        $subtotal = $this->cart->subtotal();

        $order = $this->orders->place(
            customer: $customer,
            cartItems: array_values($this->cart->items()),
            subtotal: $subtotal,
        );

        $gateway = $this->payments->resolve($customer->paymentMethod);

        try {
            $payment = $gateway->charge(new PaymentIntentData(
                order: $order,
                method: $customer->paymentMethod,
                amount: $subtotal,
                customerEmail: $customer->customerEmail,
                customerName: $customer->customerName,
                customerWhatsapp: $customer->customerWhatsapp,
            ));
        } catch (PaymentGatewayException $exception) {
            $this->orders->markPayment($order, PaymentStatus::Failed, meta: [
                'error' => $exception->getMessage(),
            ]);

            throw $exception;
        }

        $this->orders->markPayment(
            order: $order,
            status: $payment->status,
            transactionId: $payment->transactionId,
            meta: $payment->meta,
        );

        $this->cart->clear();

        $order = $order->fresh(['items']);
        $this->ordersNotifier->placed($order);

        return new PlaceOrderResult(
            order: $order,
            payment: $payment,
            whatsappLink: $this->buildWhatsappLink($order, $customer),
        );
    }

    public function orderDetails(Order $order): Order
    {
        return $this->orders->findWithItemsOrFail($order->id);
    }

    public function syncReturnedPayment(string $orderNumber, string $paymentId): Order
    {
        $order = $this->orders->findByNumber($orderNumber);

        if (! $order) {
            throw PaymentGatewayException::mismatch();
        }

        $payment = $this->paymentsLookup->lookup($paymentId);

        if (($payment->meta['external_reference'] ?? null) !== $order->order_number) {
            throw PaymentGatewayException::mismatch();
        }

        return $this->orders->markPayment(
            order: $order,
            status: $payment->status,
            transactionId: $payment->transactionId,
            meta: $payment->meta,
        );
    }

    public function syncWebhookPayment(string $paymentId): void
    {
        $payment = $this->paymentsLookup->lookup($paymentId);
        $reference = $payment->meta['external_reference'] ?? null;

        if (! is_string($reference) || $reference === '') {
            return;
        }

        $order = $this->orders->findByNumber($reference);

        if (! $order) {
            return;
        }

        $this->orders->markPayment(
            order: $order,
            status: $payment->status,
            transactionId: $payment->transactionId,
            meta: $payment->meta,
        );
    }

    private function guardAgainstEmptyCart(): void
    {
        if ($this->cart->isEmpty()) {
            throw EmptyCartException::make();
        }
    }

    private function buildWhatsappLink(Order $order, CheckoutCustomerData $customer): string
    {
        $total = number_format((float) $order->total, 2, ',', '.');
        $message = rawurlencode(
            "Olá! Fiz o pedido {$order->order_number} na AMpratas. Total: R$ {$total}"
        );

        return "https://wa.me/55{$customer->whatsappDigits()}?text={$message}";
    }
}
