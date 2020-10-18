<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $validator = Validator::make($request->all(), Product::$creation_rules);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $uploadFolder = 'products';
        $image = $request->file('image');
        $image_uploaded_path = $image->store($uploadFolder, 'public');

        $product = Product::create( array_merge(
            $validator->validated(),
            [   'sku' => strtoupper($request->sku),
                'image' =>  Storage::disk('public')->url($image_uploaded_path)
            ]
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

        $validator = Validator::make($request->all(), array_merge(
            [
                'sku' => 'string|between:8,14|unique:products,sku,'.$product->id.',id'
            ],
            Product::$update_rules
        ));

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $input = $request->all();
        $image_url = $product->image;
        $product->fill($input);

        if($request->has('image')){
            Storage::disk('public')->delete('products/'. basename($image_url));
            $uploadFolder = 'products';
            $image = $request->file('image');
            $image_uploaded_path = $image->store($uploadFolder, 'public');
            $product->image = Storage::disk('public')->url($image_uploaded_path);
        }

        $product->save();

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

        if(Storage::disk('public')->delete('products/'. basename($product->image))){
            $product->delete();
            return response()->json([
                'message' => 'Product successfully deleted'
            ]);
        }

        return response()->json([
            'message' => 'Could not delete product'
        ], 500);
    }

    private function recordNotFound(){
        return response()->json([
            'message' => 'Record not found',
        ], 404);
    }

}
