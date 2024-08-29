<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Helpers\ResponseHelper;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $helper = new ResponseHelper();
            $data = Product::select("products.name as productName", "categories.name as categoryName", "products.price as productPrice")
                    ->join('categories', 'products.category_id', '=', 'categories.id')->get();

            return $helper->responseMessageData('Product retrieved successfully', $data);
        } catch (\Throwable $th) {
            return $helper->responseError($th->getMessage(), 400);
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        try {
            $helper = new ResponseHelper();


            $validator = Validator::make($request->all(), [
                'name' => ['required'],
                'category_id' => ['required', Rule::exists('categories', 'id')],
                'price' => ['required', 'numeric']
            ]);

            if ($validator->fails()) return $helper->responseError($validator->errors(), 400);

            $data = Product::create($request->all());

            return $helper->responseMessageData('Product created successfully', $data);
        } catch (\Throwable $th) {
            return $helper->responseError($th->getMessage(), 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $helper = new ResponseHelper();
            $data = Product::find($id);

            if(empty($data)) return $helper->responseError('Data not found, wrong ID!', 404);

            return $helper->responseMessageData('Product retrieved successfully', $data);
        } catch (\Throwable $th) {
            return $helper->responseError($th->getMessage(), 400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, $id)
    {
        try {
            $helper = new ResponseHelper();
            $data = Product::find($id);

            $validator = Validator::make($request->all(), [
                'name' => ['required'],
                'category_id' => ['required', Rule::exists('categories', 'id')],
                'price' => ['required', 'numeric']
            ]);

            if ($validator->fails()) return $helper->responseError($validator->errors(), 400);

            $data->update($request->all());

            return $helper->responseMessageData('Product updated successfully', $data);
        } catch (\Throwable $th) {
            return $helper->responseError($th->getMessage(), 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $helper = new ResponseHelper();
            $data = Product::find($id);

            if(empty($data)) return $helper->responseError('Product was not found', 404);

            $data->delete();
            return $helper->responseMessageData('Product deleted successfully', $data);
        } catch (\Throwable $th) {
            return $helper->responseError($th->getMessage(), 400);
        }
    }
}
