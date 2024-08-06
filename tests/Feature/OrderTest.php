<?php

namespace Tests\Feature;

use Tests\TestCase;

class OrderTest extends TestCase
{
    public function test_order_success_return_same_data_when_currency_is_not_usd(): void
    {
        $orderData = [
            'id' => 'A0000001',
            'name' => 'Melody Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road'
            ],
            'price' => '2000',
            'currency' => 'TWD'
        ];

        $response = $this->postJson('/api/orders', $orderData);
        $response->assertStatus(200)->assertJson($orderData);
    }

    public function test_order_format_wrong_than_return_error(): void
    {
        $orderData = [];
        $exceptError = [
            "message" => "The id field is required. (and 7 more errors)",
            "errors" => [
                "id" => ["The id field is required."],
                "address" => ["The address field is required."],
                "address.city" => ["The address.city field is required."],
                "address.district" => ["The address.district field is required."],
                "address.street" => ["The address.street field is required."],
                "price" => ["The price field is required."],
                "currency" => ["The currency field is required."]
            ]
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->postJson('/api/orders', $orderData);
        $response->assertStatus(422)->assertJson($exceptError);
    }

    public function test_order_name_is_contains_non_English_than_return_error(): void
    {
        $orderData = [
            'id' => 'A0000001',
            'name' => 'Melody Ho5liday I_nn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road'
            ],
            'price' => '2000',
            'currency' => 'TWD'
        ];
        $exceptError = ["message" => "Name contains non-English characters"];

        $response = $this->postJson('/api/orders', $orderData);
        $response->assertStatus(400)->assertJson($exceptError);
    }

    public function test_order_name_is_not_capitalized_than_return_error(): void
    {
        $orderData = [
            'id' => 'A0000001',
            'name' => 'Melody holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road'
            ],
            'price' => '2000',
            'currency' => 'TWD'
        ];
        $exceptError = ["message" => "Name is not capitalized"];

        $response = $this->postJson('/api/orders', $orderData);
        $response->assertStatus(400)->assertJson($exceptError);
    }

    public function test_order_price_greater_than_2000_than_return_error(): void
    {
        $orderData = [
            'id' => 'A0000001',
            'name' => 'Melody Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road'
            ],
            'price' => '2050',
            'currency' => 'TWD'
        ];
        $exceptError = ["message" => "Price is over 2000"];

        $response = $this->postJson('/api/orders', $orderData);
        $response->assertStatus(400)->assertJson($exceptError);
    }

    public function test_order_currency_format_wrong_than_return_error(): void
    {
        $orderData = [
            'id' => 'A0000001',
            'name' => 'Melody Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road'
            ],
            'price' => '20',
            'currency' => 'JPY'
        ];
        $exceptError = ["message" => "Currency format is wrong"];

        $response = $this->postJson('/api/orders', $orderData);
        $response->assertStatus(400)->assertJson($exceptError);
    }

    public function test_order_success_return_transform_data_when_currency_is_usd(): void
    {
        $orderData = [
            'id' => 'A0000001',
            'name' => 'Melody Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road'
            ],
            'price' => '2000',
            'currency' => 'USD'
        ];
        $exceptOrderData = [
            'id' => 'A0000001',
            'name' => 'Melody Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road'
            ],
            'price' => '62000',
            'currency' => 'TWD'
        ];

        $response = $this->postJson('/api/orders', $orderData);
        $response->assertStatus(200)->assertJson($exceptOrderData);
    }
}
