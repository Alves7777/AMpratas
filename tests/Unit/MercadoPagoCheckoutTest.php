<?php

namespace Tests\Unit;

use App\Application\DTOs\PaymentIntentData;
use App\Domain\Enums\PaymentMethod;
use App\Domain\Enums\PaymentStatus;
use App\Infrastructure\Payment\MercadoPago\Preference;
use App\Infrastructure\Payment\MercadoPagoPaymentGateway;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MercadoPagoCheckoutTest extends TestCase
{
    public function test_pix_preference_keeps_only_bank_transfer_methods(): void
    {
        $payload = Preference::create()
            ->item('Aliança', 1, 249.9)
            ->payer('Maria Silva', 'maria@example.com')
            ->externalReference('AM1001')
            ->only(PaymentMethod::Pix)
            ->payload();

        $excluded = array_column($payload['payment_methods']['excluded_payment_types'], 'id');

        $this->assertArrayNotHasKey('default_payment_method_id', $payload['payment_methods']);
        $this->assertContains('credit_card', $excluded);
        $this->assertContains('debit_card', $excluded);
        $this->assertNotContains('bank_transfer', $excluded);
        $this->assertSame(249.9, $payload['items'][0]['unit_price']);
    }

    public function test_debit_preference_excludes_credit_and_pix_types(): void
    {
        $excluded = array_column(
            Preference::create()->only(PaymentMethod::DebitCard)->payload()['payment_methods']['excluded_payment_types'],
            'id',
        );

        $this->assertContains('credit_card', $excluded);
        $this->assertContains('bank_transfer', $excluded);
        $this->assertNotContains('debit_card', $excluded);
        $this->assertNotContains('account_money', $excluded);
    }

    public function test_status_mapping(): void
    {
        $this->assertSame(PaymentStatus::Paid, PaymentStatus::fromMercadoPago('approved'));
        $this->assertSame(PaymentStatus::Failed, PaymentStatus::fromMercadoPago('rejected'));
        $this->assertSame(PaymentStatus::AwaitingPayment, PaymentStatus::fromMercadoPago('pending'));
    }

    public function test_charge_redirects_to_sandbox_and_uses_the_test_buyer(): void
    {
        config([
            'payments.mercadopago.access_token' => 'TEST-token',
            'payments.mercadopago.sandbox' => true,
            'payments.mercadopago.test_payer_email' => 'buyer@testuser.com',
            'app.url' => 'http://localhost:8090',
        ]);

        Http::fake([
            'api.mercadopago.com/checkout/preferences' => Http::response([
                'id' => 'pref-1',
                'sandbox_init_point' => 'https://sandbox.mercadopago.com.br/checkout/v1/redirect?pref_id=pref-1',
                'init_point' => 'https://www.mercadopago.com.br/checkout/v1/redirect?pref_id=pref-1',
            ]),
        ]);

        $order = new Order(['order_number' => 'AM1001', 'total' => 249.9]);
        $order->setRelation('items', collect([
            new OrderItem([
                'product_name' => 'Par de alianças',
                'quantity' => 1,
                'unit_price' => 249.9,
            ]),
        ]));

        $result = app(MercadoPagoPaymentGateway::class)->charge(new PaymentIntentData(
            order: $order,
            method: PaymentMethod::Pix,
            amount: 249.9,
            customerEmail: 'cliente@email.com',
            customerName: 'Maria Silva',
            customerWhatsapp: '85999999999',
        ));

        $this->assertSame(
            'https://sandbox.mercadopago.com.br/checkout/v1/redirect?pref_id=pref-1',
            $result->checkoutUrl,
        );

        Http::assertSent(function ($request): bool {
            $body = $request->data();

            return $request->url() === 'https://api.mercadopago.com/checkout/preferences'
                && $body['payer']['email'] === 'buyer@testuser.com'
                && $body['external_reference'] === 'AM1001'
                && ! in_array('bank_transfer', array_column($body['payment_methods']['excluded_payment_types'], 'id'), true)
                && ! array_key_exists('notification_url', $body);
        });
    }
}
