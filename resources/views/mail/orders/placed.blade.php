<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Pedido {{ $order->order_number }}</title>
</head>
<body style="margin:0;padding:0;background:#f3eadf;color:#241c16;font-family:Georgia,'Times New Roman',serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f3eadf;padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;background:#fbf6ef;border:1px solid #e7dccb;">
                    <tr>
                        <td style="background:#100e0c;color:#f3eadf;padding:28px 32px;letter-spacing:0.18em;font-family:Arial,sans-serif;font-size:12px;">
                            AMPRATAS
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            <p style="margin:0 0 8px;font-family:Arial,sans-serif;font-size:11px;letter-spacing:0.16em;text-transform:uppercase;color:#7a6e63;">Pedido recebido</p>
                            <h1 style="margin:0 0 16px;font-weight:normal;font-size:32px;">Olá, {{ $order->customer_name }}</h1>
                            <p style="margin:0 0 24px;font-family:Arial,sans-serif;font-size:15px;line-height:1.6;color:#241c16;">
                                Registramos o pedido <strong>{{ $order->order_number }}</strong>.
                                {{ $order->payment_status?->checkoutMessage() }}
                            </p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-family:Arial,sans-serif;font-size:14px;">
                                @foreach($order->items as $item)
                                    <tr>
                                        <td style="padding:10px 0;border-bottom:1px solid #e7dccb;">
                                            {{ $item->product_name }} × {{ $item->quantity }}
                                        </td>
                                        <td align="right" style="padding:10px 0;border-bottom:1px solid #e7dccb;">
                                            R$ {{ number_format((float) $item->line_total, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td style="padding:16px 0 0;font-size:16px;">Total</td>
                                    <td align="right" style="padding:16px 0 0;font-size:16px;">
                                        <strong>R$ {{ number_format((float) $order->total, 2, ',', '.') }}</strong>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:24px 0 0;font-family:Arial,sans-serif;font-size:13px;line-height:1.6;color:#7a6e63;">
                                Pagamento: {{ $order->payment_method?->label() ?? 'A combinar' }}
                                · {{ $order->payment_status?->label() ?? 'Pendente' }}
                            </p>

                            @if($order->shipping_address || $order->shipping_city)
                                <p style="margin:12px 0 0;font-family:Arial,sans-serif;font-size:13px;line-height:1.6;color:#7a6e63;">
                                    Entrega: {{ $order->shipping_address }}
                                    @if($order->shipping_city)
                                        · {{ $order->shipping_city }}@if($order->shipping_state)/{{ $order->shipping_state }}@endif
                                    @endif
                                </p>
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
