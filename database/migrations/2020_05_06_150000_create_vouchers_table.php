<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tipoff\Checkout\Models\Order;
use Tipoff\Vouchers\Models\VoucherType;

class CreateVouchersTable extends Migration
{
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 9)->index()->unique();

            // Purchase fields
            $table->foreignIdFor(app('user'));
            $table->foreignIdFor(app('location'));
            $table->string('source');
            $table->foreignIdFor(VoucherType::class)->nullable();
            $table->foreignIdFor(Order::class, 'purchase_order_id')->nullable();

            // Redemption fields
            $table->dateTime('redeemable_at'); // Defaults to 24 hours after created_at
            $table->foreignIdFor(Order::class)->nullable();
            $table->dateTime('redeemed_at')->nullable();

            // Value - Vouchers can also be for 1 or more participants instead of an amount. Later could add ability for vouchers to be for a particular product like redeeming a free t-shirt
            $table->unsignedInteger('amount')->nullable(); // Amount is in cents.
            $table->unsignedTinyInteger('participants')->nullable();
            $table->date('expires_at')->nullable(); // Some vouchers have the ability to expire similar to Groupon and most gift cards/certificates

            $table->foreignIdFor(app('user'), 'creator_id');
            $table->foreignIdFor(app('user'), 'updater_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }
}
