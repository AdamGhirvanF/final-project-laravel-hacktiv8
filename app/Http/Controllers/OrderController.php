<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $helper = new ResponseHelper();
            $data = Order::all();

            return $helper->responseMessageData('Order retrieved successfully', $data);
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
    public function store(StoreOrderRequest $request)
    {
        try {
            $helper = new ResponseHelper();

            $dataValidate = [
                'user_id' => Auth::user()->id,
                'order_date' => date('Y-m-d H:i:s'),
            ];

            $dataValidate = $request->validate([
                'product_id' => ['required'],
                'quantity' => ['required', 'numeric'],
                'total_price' => ['required','numeric'],
            ]);

            $data = Order::create($dataValidate);

            return $helper->responseMessageData('Order created successfully', $data);
        } catch (\Throwable $th) {
            return $helper->responseError($th->getMessage(), 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
