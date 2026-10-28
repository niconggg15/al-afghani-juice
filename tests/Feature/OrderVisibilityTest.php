<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Tests\TestCase;

class OrderVisibilityTest extends TestCase
{
    public function test_demo_flag_is_mass_assignable_on_orders()
    {
        $this->assertContains('is_demo', (new Order())->getFillable());
    }

    public function test_demo_account_only_queries_demo_orders()
    {
        $this->actingAs(new User(['username' => 'juiceenak']));

        $query = Order::query()->visibleToCurrentAccount();

        $this->assertEquals([true], $query->getBindings());
    }

    public function test_regular_account_only_queries_production_orders()
    {
        $this->actingAs(new User(['username' => 'admin']));

        $query = Order::query()->visibleToCurrentAccount();

        $this->assertEquals([false], $query->getBindings());
    }
}
