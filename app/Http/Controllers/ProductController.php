<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Validator;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $product = Product::latest();

        if ($request->sku) {
            $product->where('sku', 'like', '%'.strtoupper($request->sku).'%');
        }

        if ($request->name) {
            $product->where('name', 'like', '%'.strtoupper($request->name).'%');
        }

        return response()->json($product->paginate(15));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sku' => 'required|string|between:8,14|unique:products',
            'name' => 'required|string|between:2,100',
            'qty' => 'required|numeric|min:0|not_in:0',
            'amount' => 'required|numeric|min:0|not_in:0',
            'description' => 'required|min:5',
            'image' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $product = Product::create( array_merge(
            $validator->validated(),
            ['sku' => strtoupper($request->sku)]
        ));

        return response()->json([
            'message' => 'Product successfully registered',
            'product' => $product
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $product = Product::find($id);

        if ( ! $product)
        {
            return $this->recordNotFound();
        }

        return response()->json($product);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {

        $product = Product::find($id);

        if ( ! $product)
        {
            return $this->recordNotFound();
        }


        $validator = Validator::make($request->all(), [
            'sku' => 'string|between:8,14|unique:products',
            'name' => 'string|between:2,100',
            'qty' => 'numeric|min:0|not_in:0',
            'amount' => 'numeric|min:0|not_in:0',
            'description' => 'min:5'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $input = $request->all();
        $product->fill($input)->save();

        return response()->json([
            'message' => 'Product successfully updated',
            'product' => $product
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if ( ! $product)
        {
            return $this->recordNotFound();
        }

        $product->delete();

        return response()->json([
            'message' => 'Product successfully deleted'
        ]);
    }

    private function recordNotFound(){
        return response()->json([
            'message' => 'Record not found',
        ], 404);
    }

}
