<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\OrderRequest;

class OrderRequestTest extends TestCase
{
    public function test_authorize_method_returns_true(): void
    {
        $request = new OrderRequest();
        $authorize = $request->authorize();

        $this->assertTrue($authorize);
    }

    public function test_rules_method_returns_rules(): void
    {
        $expectRules = [
            'id' => 'required|string',
            'name' => 'required|string',
            'address' => 'required|array',
            'address.city' => 'required|string',
            'address.district' => 'required|string',
            'address.street' => 'required|string',
            'price' => 'required|integer',
            'currency' => 'required|string'
        ];

        $request = new OrderRequest();
        $actualRules = $request->rules();

        $this->assertSame($expectRules, $actualRules);
    }

    public function test_validator_return_Validator_make(): void
    {
        $orderJson = "{\"testOrderData\": true}";
        $request = new class() extends OrderRequest {
            public $orderJson = '';
            public function getContent(bool $asResource = false) {
                return $this->orderJson;
            }
        };
        $request->orderJson = $orderJson;

        $expectResult = 'testResult';
        Validator::shouldReceive('make')->with(
            json_decode($orderJson, true),
            $request->rules(),
            $request->messages(),
            $request->attributes()
        )->once()->andReturn($expectResult);
        $actualResult = $request->validator();

        $this->assertSame($expectResult, $actualResult);
    }
}