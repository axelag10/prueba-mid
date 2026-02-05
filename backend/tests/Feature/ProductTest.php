<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Spatie\Permission\Models\Role;
use Laravel\Sanctum\Sanctum;

use App\Models\User;
use App\Models\Product;
use App\Models\Provider;
use App\Models\Category;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'Admin', 'guard_name' => 'web']);
        Role::create(['name' => 'Manager', 'guard_name' => 'web']);
        Role::create(['name' => 'Viewer', 'guard_name' => 'web']);
    }

    public function test_admin_can_create_product(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $provider = Provider::factory()->create();
        $category = Category::factory()->create();

        Sanctum::actingAs($admin);
        $response = $this
            ->postJson('/api/products', [
                'sku' => 'TEST-001',
                'name' => 'Producto Test',
                'type' => 'simple',
                'price' => 100,
                'cost' => 50,
                'provider_id' => $provider->id,
                'categories' => [$category->id],
            ]);
            // dd($response->json());

        $response->assertStatus(201);

        $this->assertDatabaseHas('products', [
            'sku' => 'TEST-001'
        ]);
    }

    public function test_viewer_cannot_create_product(): void
    {
        $viewer = User::factory()->create();
        $viewer->assignRole('Viewer');

        $provider = Provider::factory()->create();
        $category = Category::factory()->create();

        Sanctum::actingAs($viewer);
        $response = $this
            ->postJson('/api/products', [
                'sku' => 'FAIL-001',
                'name' => 'No permitido',
                'type' => 'simple',
                'price' => 100,
                'cost' => 50,
                'provider_id' => $provider->id,
                'categories' => [$category->id],
            ]);

        $response->assertStatus(403);
    }
}