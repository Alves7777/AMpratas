<?php

namespace App\Http\Controllers\Store;

use App\Application\Services\CheckoutService;
use App\Domain\Enums\PaymentMethod;
use App\Domain\Exceptions\EmptyCartException;
use App\Domain\Exceptions\PaymentGatewayException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Store\CheckoutRequest;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

final class CheckoutController extends Controller
{
    public function __construct(
        private readonly CheckoutService $checkoutService,
    ) {}

    public function index(): View|RedirectResponse
    {
        try {
            return view('store.checkout', [
                ...$this->checkoutService->checkoutView()->toArray(),
                'paymentMethods' => PaymentMethod::available(),
                'mercadoPagoSandbox' => (bool) config('payments.mercadopago.sandbox')
                    && filled(config('payments.mercadopago.access_token')),
            ]);
        } catch (EmptyCartException $exception) {
            return redirect()
                ->route('cart.index')
                ->with('error', $exception->getMessage());
        }
    }

    public function store(CheckoutRequest $request): RedirectResponse
    {
        try {
            $result = $this->checkoutService->placeOrder($request->toDto());
        } catch (EmptyCartException $exception) {
            return redirect()
                ->route('cart.index')
                ->with('error', $exception->getMessage());
        } catch (PaymentGatewayException $exception) {
            return back()
                ->withInput()
                ->with('error', $exception->getMessage());
        }

        if ($result->payment->checkoutUrl) {
            return redirect()->away($result->payment->checkoutUrl);
        }

        return redirect()
            ->route('checkout.success', $result->order)
            ->with('whatsapp_link', $result->whatsappLink)
            ->with('payment_message', $result->payment->message);
    }

    public function success(Order $order): View
    {
        return view('store.success', [
            'order' => $this->checkoutService->orderDetails($order),
        ]);
    }

    public function mercadopagoReturn(Request $request): RedirectResponse
    {
        $orderNumber = (string) $request->query('external_reference', '');
        $paymentId = (string) ($request->query('payment_id') ?: $request->query('collection_id') ?: '');

        if ($orderNumber === '' || $paymentId === '') {
            return redirect()
                ->route('home')
                ->with('error', 'O Mercado Pago não devolveu os dados do pagamento.');
        }

        try {
            $order = $this->checkoutService->syncReturnedPayment($orderNumber, $paymentId);
        } catch (PaymentGatewayException $exception) {
            return redirect()
                ->route('home')
                ->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('checkout.success', $order)
            ->with('payment_message', $order->payment_status->checkoutMessage());
    }

    public function mercadopagoWebhook(Request $request): Response
    {
        $topic = (string) ($request->input('type') ?: $request->query('topic', ''));
        $paymentId = (string) ($request->input('data.id') ?: $request->query('id', ''));

        if ($paymentId !== '' && ($topic === '' || $topic === 'payment')) {
            try {
                $this->checkoutService->syncWebhookPayment($paymentId);
            } catch (PaymentGatewayException) {
                return response('pagamento ignorado', 200);
            }
        }

        return response('ok', 200);
    }
}
