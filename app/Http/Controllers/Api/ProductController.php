<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductReq;
use App\Models\Product;
use Illuminate\Http\Request;
use Nette\Utils\Json;

class ProductController extends Controller
{
    //
    public function index()
    {
        $products = Product::paginate(3);

        return response()->json(['products' => $products], 200);
    }

    public function store(ProductReq $request)
    {
        $product = Product::create([
            'name' => $request->name,
            'detail' => $request->detail,
        ]);

        return response()->json(["isSuccess" => true, "product" => $product], 200);
    }

    public function update(ProductReq $productReq, $product)
    {
        $productRes = Product::where('id', $product)->update([
            "name" => $productReq->name,
            "detail" => $productReq->detail,
            "updated_at" => \DB::raw('CURRENT_TIMESTAMP')
        ]);
        return response()->json(["isSuccess" => true, "product" => $productRes]);
    }

    public function destroy($product)
    {
        Product::where('id', $product)->delete();

        return response()->json(["isSuccess" => true]);
    }
}
