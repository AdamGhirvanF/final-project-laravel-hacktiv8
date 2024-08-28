<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{

    public function index()
    {
        try {
            $helper = new ResponseHelper();
            $data = Category::all();

            return $helper->responseMessageData('Category retrieved successfully', $data);
        } catch (\Throwable $th) {
            return $helper->responseError($th->getMessage());
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

            $dataValidate = $request->validate([
                'name' => ['required'],
            ]);

            $data = Category::create($dataValidate);

            return $helper->responseMessageData('Category created successfully', $data);
        } catch (\Throwable $th) {
            return $helper->responseError($th->getMessage());
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

            if(empty($data)) return $helper->responseError('Data not found, wrong ID!');

            return $helper->responseMessageData('Category retrieved successfully', $data);
        } catch (\Throwable $th) {
            return $helper->responseError($th->getMessage());
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

            $dataValidate = $request->validate([
                'name' => ['required']
            ]);

            $data->update($dataValidate);

            return $helper->responseMessageData('Category updated successfully', $data);
        } catch (\Throwable $th) {
            return $helper->responseError($th->getMessage());
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

            if(empty($data)) return $helper->responseError('Category was not found');

            $data->delete();
            return $helper->responseMessageData('Category deleted successfully', $data);
        } catch (\Throwable $th) {
            return $helper->responseError($th->getMessage());
        }
    }
}
