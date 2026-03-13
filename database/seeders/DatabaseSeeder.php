<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Check if items already exist
        if (Item::count() > 0) {
            return; // Don't seed items if they already exist
        }

        // Get laundry from existing user or first laundry
        $laundryId = null;
        $user = User::first();
        if ($user && $user->laundry_id) {
            $laundryId = $user->laundry_id;
        }

        if (!$laundryId) {
            // If no user with laundry exists, we can't seed items
            return;
        }

        // Seed items by category
        $items = [
            // Executive Wear
            ['name' => 'Suit', 'category' => 'Executive Wear', 'price' => 50.00],
            ['name' => 'Suit (3 pieces)', 'category' => 'Executive Wear', 'price' => 60.00],
            ['name' => 'Jacket', 'category' => 'Executive Wear', 'price' => 30.00],
            ['name' => 'Shirt', 'category' => 'Executive Wear', 'price' => 15.00],
            ['name' => 'Trouser', 'category' => 'Executive Wear', 'price' => 20.00],
            ['name' => 'Graduation Gown', 'category' => 'Executive Wear', 'price' => 100.00],
            ['name' => 'Kids Graduation Gown', 'category' => 'Executive Wear', 'price' => 50.00],
            ['name' => 'Uniforms', 'category' => 'Executive Wear', 'price' => 60.00],
            ['name' => 'School Uniform', 'category' => 'Executive Wear', 'price' => 300.00],

            // Native Wear
            ['name' => 'Kente Kaba & Slit', 'category' => 'Native Wear', 'price' => 80.00],
            ['name' => 'Kaba & Slit', 'category' => 'Native Wear', 'price' => 50.00],
            ['name' => 'Beaded Kente Kaba & Slit', 'category' => 'Native Wear', 'price' => 100.00],
            ['name' => 'Kaftan', 'category' => 'Native Wear', 'price' => 60.00],
            ['name' => 'Kaftan & Agbada', 'category' => 'Native Wear', 'price' => 100.00],
            ['name' => 'Smock', 'category' => 'Native Wear', 'price' => 50.00],
            ['name' => 'Jarabia', 'category' => 'Native Wear', 'price' => 50.00],
            ['name' => 'White Smock', 'category' => 'Native Wear', 'price' => 70.00],
            ['name' => "Men's Cloth", 'category' => 'Native Wear', 'price' => 50.00],
            ['name' => "Men's Cloth Kente", 'category' => 'Native Wear', 'price' => 80.00],

            // Ladies Wear
            ['name' => 'Mini Straight Dress', 'category' => 'Ladies Wear', 'price' => 30.00],
            ['name' => 'Large Straight Dress', 'category' => 'Ladies Wear', 'price' => 60.00],
            ['name' => 'Gown', 'category' => 'Ladies Wear', 'price' => 80.00],
            ['name' => 'Wedding Gown', 'category' => 'Ladies Wear', 'price' => 150.00],
            ['name' => 'Ball Gown', 'category' => 'Ladies Wear', 'price' => 300.00],
            ['name' => 'Beaded Wedding Gown', 'category' => 'Ladies Wear', 'price' => 200.00],
            ['name' => 'Mini Skirt', 'category' => 'Ladies Wear', 'price' => 20.00],
            ['name' => 'Large Skirt', 'category' => 'Ladies Wear', 'price' => 30.00],
            ['name' => 'XL Skirt', 'category' => 'Ladies Wear', 'price' => 50.00],

            // Bedding & Decor
            ['name' => 'Blanket Single', 'category' => 'Bedding & Decor', 'price' => 50.00],
            ['name' => 'Blanket Double', 'category' => 'Bedding & Decor', 'price' => 100.00],
            ['name' => 'Blanket Queen Size', 'category' => 'Bedding & Decor', 'price' => 150.00],
            ['name' => 'Blanket King Size', 'category' => 'Bedding & Decor', 'price' => 200.00],
            ['name' => 'Duvet Double', 'category' => 'Bedding & Decor', 'price' => 80.00],
            ['name' => 'Duvet Queen Size', 'category' => 'Bedding & Decor', 'price' => 120.00],
            ['name' => 'Duvet King Size', 'category' => 'Bedding & Decor', 'price' => 150.00],
            ['name' => 'Bedsheet Single', 'category' => 'Bedding & Decor', 'price' => 30.00],
            ['name' => 'Bedsheet Double', 'category' => 'Bedding & Decor', 'price' => 60.00],
            ['name' => 'Bedsheet Large', 'category' => 'Bedding & Decor', 'price' => 80.00],
            ['name' => 'Pillow', 'category' => 'Bedding & Decor', 'price' => 30.00],
            ['name' => 'Curtains (per KG)', 'category' => 'Bedding & Decor', 'price' => 20.00],
            ['name' => 'Towel Mini', 'category' => 'Bedding & Decor', 'price' => 20.00],
            ['name' => 'Towel Big', 'category' => 'Bedding & Decor', 'price' => 40.00],
            ['name' => 'Towel White', 'category' => 'Bedding & Decor', 'price' => 60.00],
            ['name' => 'Towel XL', 'category' => 'Bedding & Decor', 'price' => 80.00],
            ['name' => 'Cushion Cover (per KG)', 'category' => 'Bedding & Decor', 'price' => 20.00],
            ['name' => 'Door Mat Small', 'category' => 'Bedding & Decor', 'price' => 20.00],
            ['name' => 'Door Mat', 'category' => 'Bedding & Decor', 'price' => 40.00],

            // Bag Wash (per KG)
            ['name' => 'Bag Wash (per KG)', 'category' => 'Bag Wash', 'price' => 18.00],

            // Washing
            ['name' => 'Sneakers', 'category' => 'Washing', 'price' => 40.00],
            ['name' => 'White Sneakers', 'category' => 'Washing', 'price' => 60.00],
            ['name' => 'Kids Sneakers', 'category' => 'Washing', 'price' => 20.00],
            ['name' => 'Side Bag', 'category' => 'Washing', 'price' => 30.00],
            ['name' => 'Back Pack', 'category' => 'Washing', 'price' => 50.00],
            ['name' => 'Brief Case', 'category' => 'Washing', 'price' => 50.00],
            ['name' => 'Hand Bag', 'category' => 'Washing', 'price' => 30.00],
            ['name' => 'Travelling Bag', 'category' => 'Washing', 'price' => 60.00],
            ['name' => 'Traveller', 'category' => 'Washing', 'price' => 80.00],
            ['name' => 'Mini Carpet', 'category' => 'Washing', 'price' => 150.00],
            ['name' => 'Large Carpet', 'category' => 'Washing', 'price' => 200.00],
            ['name' => 'XL Carpet', 'category' => 'Washing', 'price' => 250.00],
            ['name' => 'XXL Carpet', 'category' => 'Washing', 'price' => 300.00],
            ['name' => 'Slippers', 'category' => 'Washing', 'price' => 10.00],
            ['name' => 'Sandals', 'category' => 'Washing', 'price' => 20.00],

            // Casual Wear
            ['name' => 'Jeans', 'category' => 'Casual Wear', 'price' => 15.00],
            ['name' => 'Khaki', 'category' => 'Casual Wear', 'price' => 15.00],
            ['name' => 'Hoodie', 'category' => 'Casual Wear', 'price' => 30.00],
            ['name' => 'Cap', 'category' => 'Casual Wear', 'price' => 15.00],
            ['name' => 'T-Shirt', 'category' => 'Casual Wear', 'price' => 12.00],
            ['name' => 'Shorts', 'category' => 'Casual Wear', 'price' => 10.00],

            // Deep Cleaning (service - price varies)
            ['name' => 'Deep Cleaning (Price on arrival)', 'category' => 'Deep Cleaning', 'price' => 0.00],

            // Sofa Cleaning (service - price varies)
            ['name' => 'Sofa Cleaning (Price on arrival)', 'category' => 'Sofa Cleaning', 'price' => 0.00],
        ];

        foreach ($items as $item) {
            Item::create([
                'laundry_id' => $laundryId,
                'name' => $item['name'],
                'category' => $item['category'],
                'price' => $item['price'],
                'is_active' => true,
            ]);
        }
    }
}
