<?php

namespace App\Infrastructure\Payment;

use App\Application\DTOs\PaymentIntentData;
use App\Application\DTOs\PaymentResult;
use App\Domain\Contracts\PaymentGatewayInterface;
use App\Domain\Contracts\PaymentLookupInterface;
use App\Domain\Enums\PaymentStatus;
use App\Domain\Exceptions\PaymentGatewayException;
use App\Infrastructure\Payment\MercadoPago\MercadoPagoClient;
use App\Infrastructure\Payment\MercadoPago\Preference;

final class MercadoPagoPaymentGateway implements PaymentGatewayInterface, PaymentLookupInterface
{
    public function __construct(
        private readonly MercadoPagoClient $client,
    ) {}

    public function driver(): string
    {
        return 'mercadopago';
    }

    public function charge(PaymentIntentData $intent): PaymentResult
    {
        $returnUrl = route('checkout.mercadopago.return');
        $preference = Preference::create()
            ->payer($intent->customerName, $this->payerEmail($intent->customerEmail))
            ->externalReference($intent->order->order_number)
            ->only($intent->method)
            ->backUrls($returnUrl, $returnUrl, $returnUrl)
            ->notificationUrl($this->notificationUrl());

        foreach ($intent->order->items as $item) {
            $preference->item($item->product_name, (int) $item->quantity, (float) $item->unit_price);
        }

        if ($preference->payload()['items'] === []) {
            $preference->item($intent->order->order_number, 1, $intent->amount);
        }

        $session = $this->client->createPreference($preference);

        return PaymentResult::redirect($session->url, $session->id)
            ->withMeta([
                'provider' => 'mercadopago',
                'preference_id' => $session->id,
                'sandbox' => (bool) config('payments.mercadopago.sandbox'),
            ]);
    }

    public function lookup(string $paymentId): PaymentResult
    {
        $payment = $this->client->payment($paymentId);
        $status = PaymentStatus::fromMercadoPago((string) ($payment['status'] ?? ''));
        $reference = $payment['external_reference'] ?? null;

        if (! is_string($reference) || $reference === '') {
            throw PaymentGatewayException::mismatch();
        }

        return new PaymentResult(
            status: $status,
            transactionId: isset($payment['id']) ? (string) $payment['id'] : $paymentId,
            message: $status->checkoutMessage(),
            meta: [
                'provider' => 'mercadopago',
                'external_reference' => $reference,
                'status_detail' => $payment['status_detail'] ?? null,
                'payment_type' => $payment['payment_type_id'] ?? null,
                'payment_method' => $payment['payment_method_id'] ?? null,
            ],
        );
    }

    private function payerEmail(string $customerEmail): string
    {
        $testEmail = config('payments.mercadopago.test_payer_email');

        if (config('payments.mercadopago.sandbox') && is_string($testEmail) && $testEmail !== '') {
            return $testEmail;
        }

        return $customerEmail;
    }

    private function notificationUrl(): ?string
    {
        $host = parse_url((string) config('app.url'), PHP_URL_HOST);

        if (! is_string($host) || in_array($host, ['localhost', '127.0.0.1'], true)) {
            return null;
        }

        return route('checkout.mercadopago.webhook');
    }
}
