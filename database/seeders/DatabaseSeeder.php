<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\FarmerProfile;
use App\Models\Market;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ──────────────────────────────────────────────────────────
        $admin = User::create([
            'name' => 'MarketLink Admin',
            'email' => 'admin@marketlink.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '+1-555-0100',
            'address' => '1 Platform HQ, Green City',
            'is_active' => true,
        ]);

        // ── Farmers ────────────────────────────────────────────────────────
        $farmer1 = User::create([
            'name' => 'Emily Green',
            'email' => 'farmer@marketlink.com',
            'password' => Hash::make('password'),
            'role' => 'farmer',
            'phone' => '+1-555-0201',
            'address' => '42 Orchard Lane, Riverside',
            'is_active' => true,
        ]);

        $farmer2 = User::create([
            'name' => 'Tom Harvest',
            'email' => 'farmer2@marketlink.com',
            'password' => Hash::make('password'),
            'role' => 'farmer',
            'phone' => '+1-555-0202',
            'address' => '17 Farm Road, Meadowville',
            'is_active' => true,
        ]);

        // ── Customers ──────────────────────────────────────────────────────
        $customer = User::create([
            'name' => 'Sarah Thompson',
            'email' => 'customer@marketlink.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '+1-555-0301',
            'address' => '8 Maple Street, Greenville',
            'is_active' => true,
        ]);

        $customer2 = User::create([
            'name' => 'Jake Rivera',
            'email' => 'customer2@marketlink.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '+1-555-0302',
            'address' => '22 Oak Avenue, Parkside',
            'is_active' => true,
        ]);

        // ── Markets ────────────────────────────────────────────────────────
        $market1 = Market::create([
            'name' => 'Riverside Farmers Market',
            'address' => 'Central Park, Riverside',
            'city' => 'Riverside',
            'latitude' => 34.052235,
            'longitude' => -118.243683,
            'operating_days' => ['Saturday', 'Sunday'],
            'opening_time' => '07:00',
            'closing_time' => '13:00',
            'description' => 'The largest weekend farmers market in Riverside, featuring over 30 stalls of fresh local produce.',
            'is_active' => true,
            'created_by' => $admin->id,
        ]);

        $market2 = Market::create([
            'name' => 'Meadowville Green Market',
            'address' => 'Town Square, Meadowville',
            'city' => 'Meadowville',
            'latitude' => 34.062235,
            'longitude' => -118.253683,
            'operating_days' => ['Wednesday', 'Friday'],
            'opening_time' => '08:00',
            'closing_time' => '14:00',
            'description' => 'A mid-week market packed with organic vegetables, artisan bread, and fresh dairy.',
            'is_active' => true,
            'created_by' => $admin->id,
        ]);

        $market3 = Market::create([
            'name' => 'Greenville Sunday Bazaar',
            'address' => 'Heritage Square, Greenville',
            'city' => 'Greenville',
            'latitude' => 34.042235,
            'longitude' => -118.233683,
            'operating_days' => ['Sunday'],
            'opening_time' => '09:00',
            'closing_time' => '15:00',
            'description' => 'A beloved Sunday tradition with seasonal produce, honey, jams, and handmade crafts.',
            'is_active' => true,
            'created_by' => $admin->id,
        ]);

        // ── Farmer Profiles ────────────────────────────────────────────────
        FarmerProfile::create([
            'user_id' => $farmer1->id,
            'stall_name' => "Emily's Fresh Harvest",
            'bio' => 'Family-run organic farm specializing in seasonal vegetables and heirloom tomatoes. We have been farming the Riverside valley for over 20 years.',
            'contact_person' => 'Emily Green',
            'market_ids' => [$market1->id, $market3->id],
            'operating_days' => ['Saturday', 'Sunday'],
            'pickup_windows' => [
                ['day' => 'Saturday', 'from' => '07:00', 'to' => '12:00'],
                ['day' => 'Sunday', 'from' => '09:00', 'to' => '14:00'],
            ],
            'latitude' => 34.052235,
            'longitude' => -118.243683,
            'is_approved' => true,
        ]);

        FarmerProfile::create([
            'user_id' => $farmer2->id,
            'stall_name' => "Tom's Country Kitchen",
            'bio' => 'We grow wheat, corn, and raise free-range chickens. Our baked goods are made fresh every market morning using our own grain.',
            'contact_person' => 'Tom Harvest',
            'market_ids' => [$market2->id, $market3->id],
            'operating_days' => ['Wednesday', 'Sunday'],
            'pickup_windows' => [
                ['day' => 'Wednesday', 'from' => '08:00', 'to' => '13:00'],
                ['day' => 'Sunday', 'from' => '09:00', 'to' => '14:00'],
            ],
            'latitude' => 34.062235,
            'longitude' => -118.253683,
            'is_approved' => true,
        ]);

        // ── Product Categories ─────────────────────────────────────────────
        $vegetables = ProductCategory::create(['name' => 'Vegetables', 'icon' => '🥦', 'slug' => 'vegetables']);
        $fruits = ProductCategory::create(['name' => 'Fruits', 'icon' => '🍎', 'slug' => 'fruits']);
        $dairy = ProductCategory::create(['name' => 'Dairy', 'icon' => '🧀', 'slug' => 'dairy']);
        $baked = ProductCategory::create(['name' => 'Baked Goods', 'icon' => '🍞', 'slug' => 'baked-goods']);
        $herbs = ProductCategory::create(['name' => 'Herbs & Spices', 'icon' => '🌿', 'slug' => 'herbs-spices']);
        $honey = ProductCategory::create(['name' => 'Honey & Jams', 'icon' => '🍯', 'slug' => 'honey-jams']);

        // ── Products ───────────────────────────────────────────────────────
        $p1 = Product::create([
            'farmer_id' => $farmer1->id,
            'category_id' => $vegetables->id,
            'name' => 'Organic Heirloom Tomatoes',
            'description' => 'Vine-ripened heritage tomatoes, picked fresh the morning of market day. Perfect for salads and pasta sauces.',
            'price' => 4.50,
            'unit' => 'kg',
            'stock_quantity' => 50,
            'is_available' => true,
            'is_recurring' => true,
        ]);

        $p2 = Product::create([
            'farmer_id' => $farmer1->id,
            'category_id' => $vegetables->id,
            'name' => 'Baby Spinach',
            'description' => 'Tender young spinach leaves, washed and ready to eat.',
            'price' => 3.00,
            'unit' => 'bunch',
            'stock_quantity' => 30,
            'is_available' => true,
            'is_recurring' => true,
        ]);

        $p3 = Product::create([
            'farmer_id' => $farmer1->id,
            'category_id' => $fruits->id,
            'name' => 'Strawberries',
            'description' => 'Sun-kissed, sweet strawberries from our organic fields. Seasonal availability.',
            'price' => 5.00,
            'unit' => 'punnet',
            'stock_quantity' => 40,
            'is_available' => true,
            'is_recurring' => false,
        ]);

        $p4 = Product::create([
            'farmer_id' => $farmer1->id,
            'category_id' => $herbs->id,
            'name' => 'Fresh Basil',
            'description' => 'Aromatic basil, great for Italian dishes and homemade pesto.',
            'price' => 2.50,
            'unit' => 'bunch',
            'stock_quantity' => 25,
            'is_available' => true,
            'is_recurring' => true,
        ]);

        $p5 = Product::create([
            'farmer_id' => $farmer2->id,
            'category_id' => $baked->id,
            'name' => 'Sourdough Loaf',
            'description' => 'Traditional sourdough bread baked fresh every morning. Long fermentation for superior flavor.',
            'price' => 7.00,
            'unit' => 'loaf',
            'stock_quantity' => 20,
            'is_available' => true,
            'is_recurring' => true,
        ]);

        $p6 = Product::create([
            'farmer_id' => $farmer2->id,
            'category_id' => $dairy->id,
            'name' => 'Free-Range Eggs',
            'description' => 'Fresh eggs from our free-range chickens, raised on pasture.',
            'price' => 6.50,
            'unit' => 'dozen',
            'stock_quantity' => 35,
            'is_available' => true,
            'is_recurring' => true,
        ]);

        $p7 = Product::create([
            'farmer_id' => $farmer2->id,
            'category_id' => $honey->id,
            'name' => 'Raw Wildflower Honey',
            'description' => 'Unprocessed honey harvested from our meadow hives. Rich in antioxidants.',
            'price' => 12.00,
            'unit' => '500g jar',
            'stock_quantity' => 15,
            'is_available' => true,
            'is_recurring' => true,
        ]);

        $p8 = Product::create([
            'farmer_id' => $farmer2->id,
            'category_id' => $baked->id,
            'name' => 'Blueberry Muffins',
            'description' => 'Freshly baked muffins packed with wild blueberries.',
            'price' => 4.00,
            'unit' => '6-pack',
            'stock_quantity' => 18,
            'is_available' => true,
            'is_recurring' => false,
        ]);

        // ── Sample Orders ──────────────────────────────────────────────────
        $order1 = Order::create([
            'customer_id' => $customer->id,
            'farmer_id' => $farmer1->id,
            'pickup_date' => now()->addDays(3)->toDateString(),
            'pickup_slot' => '09:00-10:00',
            'status' => 'accepted',
            'total_amount' => 16.50,
            'notes' => 'Please keep the tomatoes separate.',
            'cutoff_time' => now()->addDays(2),
        ]);

        OrderItem::create(['order_id' => $order1->id, 'product_id' => $p1->id, 'quantity' => 2, 'unit_price' => 4.50]);
        OrderItem::create(['order_id' => $order1->id, 'product_id' => $p4->id, 'quantity' => 3, 'unit_price' => 2.50]);

        $order2 = Order::create([
            'customer_id' => $customer->id,
            'farmer_id' => $farmer2->id,
            'pickup_date' => now()->subDays(7)->toDateString(),
            'pickup_slot' => '10:00-11:00',
            'status' => 'completed',
            'total_amount' => 19.50,
        ]);

        OrderItem::create(['order_id' => $order2->id, 'product_id' => $p5->id, 'quantity' => 1, 'unit_price' => 7.00]);
        OrderItem::create(['order_id' => $order2->id, 'product_id' => $p6->id, 'quantity' => 1, 'unit_price' => 6.50]);
        OrderItem::create(['order_id' => $order2->id, 'product_id' => $p7->id, 'quantity' => 1, 'unit_price' => 12.00]);

        $order3 = Order::create([
            'customer_id' => $customer2->id,
            'farmer_id' => $farmer1->id,
            'pickup_date' => now()->addDays(5)->toDateString(),
            'pickup_slot' => '08:00-09:00',
            'status' => 'placed',
            'total_amount' => 15.00,
            'cutoff_time' => now()->addDays(4),
        ]);

        OrderItem::create(['order_id' => $order3->id, 'product_id' => $p3->id, 'quantity' => 2, 'unit_price' => 5.00]);
        OrderItem::create(['order_id' => $order3->id, 'product_id' => $p2->id, 'quantity' => 1, 'unit_price' => 3.00]);
        OrderItem::create(['order_id' => $order3->id, 'product_id' => $p4->id, 'quantity' => 1, 'unit_price' => 2.50]);

        // ── Reviews ────────────────────────────────────────────────────────
        Review::create([
            'order_id' => $order2->id,
            'customer_id' => $customer->id,
            'farmer_id' => $farmer2->id,
            'product_id' => $p5->id,
            'rating' => 5,
            'comment' => "Tom's sourdough is absolutely incredible! Best bread at any market I've visited.",
            'farmer_reply' => 'Thank you Sarah! We bake it fresh every morning just for you.',
            'is_visible' => true,
        ]);

        Review::create([
            'order_id' => $order2->id,
            'customer_id' => $customer->id,
            'farmer_id' => $farmer2->id,
            'product_id' => $p6->id,
            'rating' => 4,
            'comment' => 'Great eggs, very fresh yolks. Will definitely reorder.',
            'is_visible' => true,
        ]);

        // ── Announcements ──────────────────────────────────────────────────
        Announcement::create([
            'admin_id' => $admin->id,
            'title' => 'Welcome to MarketLink!',
            'body' => 'We are excited to launch MarketLink, your one-stop platform for connecting with local farmers markets. Browse nearby markets, pre-order fresh produce, and never miss your favorite farmer again!',
            'type' => 'success',
            'published_at' => now(),
        ]);

        Announcement::create([
            'admin_id' => $admin->id,
            'title' => 'Harvest Season is Here!',
            'body' => 'This autumn, enjoy an abundance of seasonal fruits, root vegetables, and artisan produce from our growing network of local farmers.',
            'type' => 'info',
            'published_at' => now()->subDay(),
        ]);
    }
}
