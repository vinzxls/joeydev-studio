<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RedesignedPagesRenderTest extends TestCase
{
    use RefreshDatabase;

    private function seedAll(): void
    {
        $this->seed();
    }

    private function createOrder(string $status = 'menunggu_konfirmasi'): Order
    {
        $subject = Subject::active()->first();

        $order = Order::create([
            'order_code' => Order::generateCode(),
            'client_name' => 'Test Klien',
            'client_wa' => '081234567890',
            'client_email' => 'klien@test.com',
            'subject_id' => $subject->id,
            'title' => 'Project Test',
            'description' => 'Deskripsi test untuk rendering.',
            'deadline' => now()->addDays(7),
            'base_price' => 100000,
            'final_price' => 130000,
            'status' => $status,
            'payment_status' => 'belum_bayar',
        ]);

        $order->statusLogs()->create([
            'status' => 'menunggu_konfirmasi',
            'note' => 'Order diterima.',
        ]);

        return $order;
    }

    public function test_home_page_renders(): void
    {
        $this->seedAll();

        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_order_create_page_renders(): void
    {
        $this->seedAll();

        $response = $this->get('/pesan');
        $response->assertStatus(200);
    }

    public function test_tracking_form_page_renders(): void
    {
        $this->seedAll();

        $response = $this->get('/cek-progress');
        $response->assertStatus(200);
    }

    public function test_order_success_page_renders(): void
    {
        $this->seedAll();

        $order = $this->createOrder();

        $response = $this->withSession([
            'track_verified_'.$order->order_code => true,
        ])->get('/pesan/sukses/'.$order->order_code);

        $response->assertStatus(200);
    }

    public function test_tracking_show_page_renders(): void
    {
        $this->seedAll();

        $order = $this->createOrder('dikerjakan');

        $response = $this->withSession([
            'track_verified_'.$order->order_code => true,
        ])->get('/cek-progress/'.$order->order_code);

        $response->assertStatus(200);
    }

    public function test_tracking_show_selesai_renders_rating_form(): void
    {
        $this->seedAll();

        $order = $this->createOrder('selesai');

        $response = $this->withSession([
            'track_verified_'.$order->order_code => true,
        ])->get('/cek-progress/'.$order->order_code);

        $response->assertStatus(200);
        $response->assertSee('Beri Rating');
    }

    public function test_tracking_show_dibatalkan_renders(): void
    {
        $this->seedAll();

        $order = $this->createOrder('dibatalkan');

        $response = $this->withSession([
            'track_verified_'.$order->order_code => true,
        ])->get('/cek-progress/'.$order->order_code);

        $response->assertStatus(200);
    }
}
