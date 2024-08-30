<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    public function index()
    {
        try {
            $helper = new ResponseHelper();
            $data = Category::all();

            return $helper->responseMessageData('Category retrieved successfully', $data);
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
    public function store(StoreCategoryRequest $request)
    {
        try {
            $helper = new ResponseHelper();

            $validator = Validator::make($request->all(), [
                'name' => 'required'
            ]);

            if ($validator->fails()) return $helper->responseError($validator->errors(), 400);

            $data = Category::create($request->all());

            return $helper->responseMessageData('Category created successfully', $data);
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
            $data = Category::find($id);

            if(empty($data)) return $helper->responseError('Data not found, wrong ID!', 404);

            return $helper->responseMessageData('Category retrieved successfully', $data);
        } catch (\Throwable $th) {
            return $helper->responseError($th->getMessage(), 400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($category)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        try {
            $helper = new ResponseHelper();
            $data = Category::find($id);

            if(empty($data)) return $helper->responseError('Wrong category ID, please check again', 400);

            $validator = Validator::make($request->all(), [
                'name' => 'required'
            ]);

            if ($validator->fails()) return $helper->responseError($validator->errors(), 400);

            $data->update($request->all());

            return $helper->responseMessageData('Category updated successfully', $data);
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
            $data = Category::find($id);

            if(empty($data)) return $helper->responseError('Category was not found', 404);
            if(!empty(Product::where('category_id', $id)->first())) return $helper->responseError('Category can not be deleted since there is product attached to it', 400);

            $data->delete();
            return $helper->responseMessageData('Category deleted successfully', $data);
        } catch (\Throwable $th) {
            return $helper->responseError($th->getMessage(), 400);
        }
    }
}
