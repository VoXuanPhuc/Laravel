<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductReq;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'created_by' => Auth::user()->id,
            'user_id' => $request->user_id,
        ]);

        return response()->json(["isSuccess" => true, "product" => $product], 200);
    }

    public function show($product)
    {
        $productRes = Product::where('id', $product)->get();
        return response()->json(["isSuccess" => true, "product" => $productRes], 200);
    }

    public function update(ProductReq $productReq, $product)
    {
        $productRes = Product::where('id', $product)->update([
            "name" => $productReq->name,
            "detail" => $productReq->detail,
            "updated_at" => \DB::raw('CURRENT_TIMESTAMP')
        ]);
        return response()->json(["isSuccess" => true, "product" => $productRes], 200);
    }

    public function destroy($product)
    {
        Product::where('id', $product)->delete();

        return response()->json(["isSuccess" => true], 200);
    }

    public function productsOfUser()
    {
        $currentUserId = Auth::user()->id;
        $productOfCurrentUser = Product::where("user_id", $currentUserId)
            ->orWhere('created_by', $currentUserId)->paginate(3);

        return response()->json(["isSuccess" => true, "products" => $productOfCurrentUser], 200);
    }

    public function assignProductToUser(Request $request, $product)
    {
        $assignedUser = $request->get("user_id");
        if (!$assignedUser) {
            $assignedUser = Auth::user()->id;
        }
        $productUpdated = Product::where('id', $product)->update(
            ['user_id' => $assignedUser]
        );
        return response()->json(["isSuccess" => true], 200);
    }

    public function updateStatusProduct($product)
    {
        $currentStatus = Product::where('id', $product)->value('status');

        $productUpdated = Product::where('id', $product)->update(
            ['status' => !$currentStatus]
        );
        return response()->json(["isSuccess" => true, "product" => $productUpdated], 200);
    }
}
