<?php

declare(strict_types=1);

namespace Tipoff\Vouchers\Tests\Unit\View\Components\Cart;

use Tipoff\Checkout\Models\Cart;
use Tipoff\Checkout\Models\CartItem;
use Tipoff\Vouchers\Models\VoucherType;
use Tipoff\Vouchers\Tests\TestCase;

class VoucherTypeComponentTest extends TestCase
{
    /** @test */
    public function single_item()
    {
        /** @var VoucherType $sellable */
        $sellable = VoucherType::factory()->amount(1234)->create();
        $cart = Cart::factory()->create();
        CartItem::factory()->withSellable($sellable)->create([
            'cart_id' => $cart,
            'quantity' => 1,
        ]);
        $cart->refresh()->save();

        $view = $this->blade(
            '<x-tipoff-cart :cart="$cart" />',
            ['cart' => $cart]
        );

        $view->assertSee("Voucher Type: {$sellable->name}");
    }
}
