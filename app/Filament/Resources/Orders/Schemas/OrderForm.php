<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Domain\Enums\OrderStatus;
use App\Domain\Enums\PaymentMethod;
use App\Domain\Enums\PaymentStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;


class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Pedido')
                    ->columns(2)
                    ->schema([
                        TextInput::make('order_number')
                            ->label('Número')
                            ->disabled(),
                        Select::make('status')
                            ->options(OrderStatus::options())
                            ->required(),
                        TextInput::make('customer_name')->label('Cliente')->required(),
                        TextInput::make('customer_email')->label('E-mail')->email()->required(),
                        TextInput::make('customer_phone')->label('Telefone'),
                        TextInput::make('customer_whatsapp')->label('WhatsApp'),
                        TextInput::make('shipping_city')->label('Cidade'),
                        TextInput::make('shipping_state')->label('UF')->maxLength(2),
                        TextInput::make('shipping_cep')->label('CEP'),
                        Textarea::make('shipping_address')->label('Endereço')->columnSpanFull(),
                        Textarea::make('notes')->label('Observações')->columnSpanFull(),
                        TextInput::make('subtotal')->numeric()->prefix('R$')->disabled(),
                        TextInput::make('total')->numeric()->prefix('R$')->disabled(),
                        Select::make('payment_method')
                            ->label('Método de pagamento')
                            ->options(collect(PaymentMethod::cases())->mapWithKeys(
                                fn (PaymentMethod $method) => [$method->value => $method->label()]
                            )->all())
                            ->disabled(),
                        Select::make('payment_status')
                            ->label('Status do pagamento')
                            ->options(collect(PaymentStatus::cases())->mapWithKeys(
                                fn (PaymentStatus $status) => [$status->value => $status->label()]
                            )->all())
                            ->required(),
                        TextInput::make('payment_transaction_id')
                            ->label('ID da transação')
                            ->disabled(),
                    ]),
            ]);
    }
}

