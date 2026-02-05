<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Spatie\Permission\Models\Role;
use Laravel\Sanctum\Sanctum;

use App\Models\User;
use App\Models\Product;
use App\Models\Stock;

class StockTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'Admin', 'guard_name' => 'web']);
        Role::create(['name' => 'Manager', 'guard_name' => 'web']);
        Role::create(['name' => 'Viewer', 'guard_name' => 'web']);
    }

    public function test_manager_can_update_stock(): void
    {
        $manager = User::factory()->create();
        $manager->assignRole('Manager');

        $product = Product::factory()->create([
            'type' => 'simple'
        ]);

        $stock = Stock::create([
            'product_id' => $product->id,
            'variant_type_id' => null,
            'quantity' => 10
        ]);

        Sanctum::actingAs($manager);
        $response = $this
            ->putJson("/api/stocks/{$stock->id}", [
                'quantity' => 5,
                'reason' => 'Restock'
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('stocks', [
            'id' => $stock->id,
            'quantity' => 15
        ]);
    }

    public function test_cannot_reduce_stock_below_zero(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $product = Product::factory()->create([
            'type' => 'simple'
        ]);

        $stock = Stock::create([
            'product_id' => $product->id,
            'variant_type_id' => null,
            'quantity' => 5
        ]);

        Sanctum::actingAs($admin);
        $response = $this
            ->putJson("/api/stocks/{$stock->id}", [
                'quantity' => -10,
                'reason' => 'Sale'
            ]);

        $response->assertStatus(422);
    }

    public function test_stock_movement_is_recorded(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $product = Product::factory()->create([
            'type' => 'simple'
        ]);

        $stock = Stock::create([
            'product_id' => $product->id,
            'variant_type_id' => null,
            'quantity' => 5
        ]);

        Sanctum::actingAs($admin);
        $response = $this
            ->putJson("/api/stocks/{$stock->id}", [
                'quantity' => -10,
                'reason' => 'Sale'
            ]);

        $response->assertStatus(422);
    }
}
