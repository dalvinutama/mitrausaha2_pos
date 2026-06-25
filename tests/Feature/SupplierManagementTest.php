<?php

namespace Tests\Feature;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'admin']);
    }

    public function test_guest_cannot_access_supplier_page(): void
    {
        $response = $this->get(route('supplier'));
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_view_supplier_page(): void
    {
        $response = $this->actingAs($this->user)->get(route('supplier'));
        $response->assertStatus(200);
    }

    public function test_admin_can_create_supplier(): void
    {
        $response = $this->actingAs($this->user)->post(route('supplier.store'), [
            'nama_supplier' => 'PT Semen Indonesia',
            'nama_pic'      => 'Budi Santoso',
            'no_hp'         => '081234567890',
            'alamat'        => 'Jl. Raya No. 1',
            'kategori_suplai' => 'Semen',
            'termin_default'  => '30 hari',
            'status'          => 'aktif',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('suppliers', ['nama_supplier' => 'PT Semen Indonesia']);
    }

    public function test_supplier_validation_requires_nama_supplier(): void
    {
        $response = $this->actingAs($this->user)->post(route('supplier.store'), [
            'nama_pic' => 'Budi',
            'no_hp'    => '081234567890',
        ]);

        $response->assertSessionHasErrors(['nama_supplier']);
    }

    public function test_admin_can_update_supplier(): void
    {
        $supplier = Supplier::factory()->create();

        $response = $this->actingAs($this->user)->put(route('supplier.update', $supplier->id), [
            'nama_supplier' => 'PT Semen Indonesia Update',
            'nama_pic'      => 'Budi Santoso',
            'no_hp'         => '081234567890',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('suppliers', ['nama_supplier' => 'PT Semen Indonesia Update']);
    }

    public function test_admin_can_delete_supplier(): void
    {
        $supplier = Supplier::factory()->create();

        $response = $this->actingAs($this->user)->delete(route('supplier.destroy', $supplier->id));

        $response->assertSessionHas('success');
        $this->assertSoftDeleted($supplier);
    }
}
