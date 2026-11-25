# Arquitetura AMpratas

## Camadas (Clean Architecture + MVC)

```
Http (Presentation)
  Controller → Form Request → View
        ↓
Application (Use Cases)
  Service + DTO
        ↓
Domain (Contracts / Enums / Exceptions)
        ↓
Infrastructure (Repositories Eloquent / Session Cart)
        ↓
Model (Eloquent)
```

## Fluxo padrão

1. **Controller** — fino, só orquestra HTTP
2. **Form Request** — validação tipada + conversão para DTO
3. **Service** — regra de negócio / caso de uso
4. **Repository** — acesso a dados (abstração)
5. **Model** — persistência Eloquent

## Padrões aplicados

| Padrão | Onde |
|--------|------|
| Repository | `Domain/Contracts` + `Infrastructure/Persistence` |
| Service / Application Service | `Application/Services` |
| DTO | `Application/DTOs` |
| Dependency Injection | `DomainServiceProvider` |
| Strategy | `ProductSort` no repositório de produtos |
| Factory | `OrderNumberGenerator` |
| Enum as Value Object | `OrderStatus`, `ProductSort` |

## Princípios

- **SOLID**: interfaces no Domain, serviços com SRP, DI (DIP)
- **YAGNI / KISS / DRY**: sem camadas extras sem uso real
- **Object Calisthenics**: early return, métodos curtos, DTOs tipados, classes `final`
- **POO**: encapsulamento via contracts e services injetáveis

## Pagamentos (pronto para API)

```
CheckoutService
  → PaymentGatewayManager::resolve(PaymentMethod)
  → PaymentGatewayInterface::charge(PaymentIntentData)
  → PaymentResult (status / checkoutUrl / transactionId)
```

- Ativo hoje: `ManualWhatsappPaymentGateway`
- Para PIX/cartão: implementar gateway (ex. Mercado Pago), setar
  `PAYMENT_PIX_ENABLED=true` / `PAYMENT_CARD_ENABLED=true` em `.env`
  e registrar no `PaymentGatewayManager`

