<?php

namespace Database\Seeders;

use App\Enums\CartEnum;
use App\Enums\OrderStatusEnum;
use App\Models\DiscountCode;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Payment;
use App\Models\Presentation;
use App\Models\Product;
use App\Models\Sku;
use App\Models\User;
use App\Services\CartService;
use App\Services\OrderService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Number;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Order::truncate();
        OrderProduct::truncate();
        Payment::truncate();

        $users = User::get();
        $discountCodes = DiscountCode::get();

        // Cargar skus con su producto asociado
        $order_products = Sku::with([
            'size:id,name',
            'product' => function ($query) {
                $query->select(
                    'id',
                    'slug',
                    'img',
                    'name',
                    'ref',
                    'thumb',
                    'price',
                    'offer',
                    'old_price',
                    'max_quantity',
                    'color_id',
                    'category_id',
                    'department_id'
                )
                ->with('color:id,name');
            }
        ])->get();

        // Filtrar SKUs sin producto (evita el error)
        $order_products = $order_products->filter(fn ($sku) => $sku->product !== null);

        foreach ($users->take(13)->multiply(10) as $user) {

            // Evitar errores si hay pocos SKUs
            $maxRandom = min(20, $order_products->count());
            $minRandom = min(10, $order_products->count());

            if ($minRandom == 0) {
                continue; // No hay SKUs válidos
            }

            $order_products_selected = $order_products
                ->random(rand($minRandom, $maxRandom))
                ->map(function ($sku) {
                    $quantity = rand(1, 14);
                    return OrderService::formatOrderProduct($sku, $quantity);
                });

            // Descuento aleatorio
            $discountCode = rand(0, 2) == 0
                ? $discountCodes->random()
                : null;

            // Generar la orden
            $order = OrderService::generateOrder($order_products_selected, $discountCode, $user);

            // Datos del usuario dentro de la orden
            $order->data = [
                'user' => $user->only('name', 'address', 'phone', 'email', 'city'),
            ];

            // Status aleatorio
            $order->status = fake()->randomElement(OrderStatusEnum::cases());

            // Si no fue exitosa, poner refund
            if ($order->status != OrderStatusEnum::SUCCESSFUL) {
                $order->refund_at = now();
            }

            // Fechas
            $order->created_at = fake()->dateTimeBetween('-12 months', 'now');
            $order->updated_at = $order->created_at;

            $order->save();

            // Crear los productos de la orden
            $order->order_products()->createMany($order_products_selected);

            // Crear pago
            Payment::factory()->create([
                'order_id' => $order->id
            ]);

            $this->command->info("Orden " . $order->code . " : " . Number::currency($order->total));
        }
    }
}
