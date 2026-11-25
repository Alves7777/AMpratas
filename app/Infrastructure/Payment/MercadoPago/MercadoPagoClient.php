<?php

namespace App\Infrastructure\Payment\MercadoPago;

use App\Domain\Exceptions\PaymentGatewayException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

final class MercadoPagoClient
{
    private const BASE_URL = 'https://api.mercadopago.com';

    public function createPreference(Preference $preference): CheckoutSession
    {
        $body = $this->post('/checkout/preferences', $preference->payload());

        $url = config('payments.mercadopago.sandbox') ? ($body['sandbox_init_point'] ?? null) : ($body['init_point'] ?? null);

        if (! is_string($url) || $url === '' || ! isset($body['id'])) {
            throw PaymentGatewayException::rejected('O Mercado Pago não devolveu o link de pagamento.');
        }

        return new CheckoutSession((string) $body['id'], $url);
    }

    /**
     * @return array<string, mixed>
     */
    public function payment(string $paymentId): array
    {
        return $this->get('/v1/payments/'.$paymentId);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function post(string $path, array $payload): array
    {
        return $this->dispatch(fn () => $this->request()->post($path, $payload));
    }

    /**
     * @return array<string, mixed>
     */
    private function get(string $path): array
    {
        return $this->dispatch(fn () => $this->request()->get($path));
    }

    private function request(): PendingRequest
    {
        $token = config('payments.mercadopago.access_token');

        if (! is_string($token) || $token === '') {
            throw PaymentGatewayException::notConfigured();
        }

        return Http::baseUrl(self::BASE_URL)
            ->withToken($token)
            ->acceptJson()
            ->asJson()
            ->timeout(20);
    }

    /**
     * @param  callable(): Response  $call
     * @return array<string, mixed>
     */
    private function dispatch(callable $call): array
    {
        try {
            $json = $call()->throw()->json();
        } catch (RequestException $exception) {
            $message = $exception->response?->json('message') ?? $exception->response?->json('error') ?? 'Não foi possível falar com o Mercado Pago.';

            throw PaymentGatewayException::rejected(is_string($message) ? $message : 'Não foi possível falar com o Mercado Pago.');
        }

        return is_array($json) ? $json : [];
    }
}
