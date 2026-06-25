<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\Product;
use App\Models\Satuan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Kategori $kategori;
    private Satuan $satuan;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'admin']);
        $this->kategori = Kategori::factory()->create(['prefix_sku' => 'TST']);
        $this->satuan = Satuan::factory()->create(['nama_satuan' => 'Unit']);
    }

    public function test_guest_cannot_access_product_page(): void
    {
        $response = $this->get(route('persediaan'));
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_view_product_page(): void
    {
        $response = $this->actingAs($this->user)->get(route('persediaan'));
        $response->assertStatus(200);
    }

    public function test_admin_can_create_product(): void
    {
        $response = $this->actingAs($this->user)->post(route('persediaan.store'), [
            'kategori_id'       => $this->kategori->id,
            'nama_barang'       => 'Semen Tiga Roda 50kg',
            'satuan'            => 'Sak',
            'harga_beli'        => 50000,
            'harga_jual'        => 65000,
            'lead_time_hari'    => 3,
            'tipe_safety_stock' => 'manual',
            'safety_stock'      => 10,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('products', ['nama_barang' => 'Semen Tiga Roda 50kg']);
    }

    public function test_product_validation_fails_with_empty_data(): void
    {
        $response = $this->actingAs($this->user)->post(route('persediaan.store'), []);
        $response->assertSessionHasErrors(['kategori_id', 'nama_barang', 'satuan', 'harga_beli', 'harga_jual', 'lead_time_hari', 'tipe_safety_stock']);
    }

    public function test_admin_can_update_product(): void
    {
        $product = Product::factory()->create(['kategori_id' => $this->kategori->id]);

        $response = $this->actingAs($this->user)->put(route('persediaan.update', $product->id), [
            'kategori_id'       => $this->kategori->id,
            'nama_barang'       => 'Semen Updated',
            'satuan'            => 'Sak',
            'harga_beli'        => 55000,
            'harga_jual'        => 70000,
            'lead_time_hari'    => 4,
            'tipe_safety_stock' => 'manual',
            'safety_stock'      => 5,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('products', ['nama_barang' => 'Semen Updated']);
    }
}
