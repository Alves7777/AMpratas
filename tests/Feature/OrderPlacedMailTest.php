<?php

namespace Tests\Feature;

use App\Infrastructure\Mail\OrderPlacedMail;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OrderPlacedMailTest extends TestCase
{
    // use RefreshDatabase;

    public function test_checkout_emails_the_customer_when_the_order_is_placed(): void
    {
        Mail::fake();

        $category = Category::query()->create([
            'name' => 'Alianças',
            'slug' => 'aliancas',
        ]);

        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Par de alianças',
            'slug' => 'par-de-aliancas',
            'price' => 249.90,
            'stock' => 4,
            'is_active' => true,
        ]);

        $this->post(route('cart.add', $product))->assertRedirect();

        $this->post(route('checkout.store'), [
            'customer_name' => 'Maria Silva',
            'customer_email' => 'maria@example.com',
            'customer_whatsapp' => '8596744989',
            'payment_method' => 'manual_whatsapp',
        ])->assertRedirect();

        Mail::assertSent(OrderPlacedMail::class, function (OrderPlacedMail $mail): bool {
            return $mail->hasTo('maria@example.com')
                && $mail->order->customer_name === 'Maria Silva'
                && str_contains($mail->envelope()->subject, (string) $mail->order->order_number)
                && $mail->order->items->contains('product_name', 'Par de alianças');
        });
    }
}
