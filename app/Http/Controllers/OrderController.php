<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $helper = new ResponseHelper();
            $data = Order::select('orders.id as id', 'product_id as productId', 'quantity',
                            'total_price as totalPrice', 'users.name as customerName', 'users.address as customerAddress',
                            'orders.created_at as createdAt', 'orders.updated_at as updatedAt')
                    ->leftJoin('users', 'orders.user_id', '=', 'users.id')
                    ->where('orders.user_id', '=', Auth::user()->id)
                    ->get();

            if($data->isEmpty()) return $helper->responseError('You dont have any order yet',400);

            return $helper->responseMessageData('Order retrieved successfully', $data);
        } catch (\Throwable $th) {
            return $helper->responseError($th->getMessage(), 400);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function report()
    {
        try {
            $helper = new ResponseHelper();

            $data = [
                "totalOrders" => Order::all()->count(),
                "totalRevenue" => Order::sum('total_price'),
                "orders" => Order::select('orders.id as id', 'products.name as productName',
                                        'quantity','total_price as totalPrice','users.name as customerName',
                                        'users.address as customerAddress', 'orders.created_at as createdAt',
                                        'orders.updated_at as updatedAt')
                    ->join('users', 'orders.user_id', '=', 'users.id')
                    ->join('products', 'orders.product_id', '=', 'products.id')
                    ->get()
            ];

            if($data['totalOrders'] == 0 && $data['totalRevenue'] == 0 && $data['orders']->isEmpty()) {
                return $helper->responseError('There are no orders yet',400);
            }

            return $helper->responseMessageData('Orders report retrieved successfully', $data);
        } catch (\Throwable $th) {
            return $helper->responseError($th->getMessage(), 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        try {
            $helper = new ResponseHelper();

            $validator = Validator::make($request->all(), [
                'product_id' => ['required'],
                'quantity' => ['required', 'numeric'],
            ]);

            if($validator->fails()) return $helper->responseError($validator->errors(), 400);

            if(Product::find($request->product_id) == null) return $helper->responseError('You input the wrong product ID!',400);

            if($request->quantity * Product::find($request->product_id)->price > 999999) return $helper->responseError('Total price maximum price is 999.999',400);

            $data = Order::create([
                'user_id' => Auth::user()->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'total_price' => $request->quantity * Product::find($request->product_id)->price,
                'order_date' => date('Y-m-d H:i:s'),
            ]);

            return $helper->responseMessageData('Order created successfully', $data);
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
            $data = Order::select('orders.id as id', 'product_id as productId', 'quantity', 'total_price as totalPrice',
                                'users.name as customerName', 'users.address as customerAddress',
                                'orders.created_at as createdAt', 'orders.updated_at as updatedAt')
                    ->leftJoin('users', 'orders.user_id', '=', 'users.id')
                    ->where('orders.id', '=', $id )
                    ->where('orders.user_id', '=', Auth::user()->id)
                    ->first();

            if(empty($data)) return $helper->responseError('Data not found, wrong ID!', 404);

            return $helper->responseMessageData('Order retrieved successfully', $data);
        } catch (\Throwable $th) {
            return $helper->responseError($th->getMessage(), 400);
        }
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
    public function update(UpdateOrderRequest $request, $id)
    {
        try {
            $helper = new ResponseHelper();
            $data = Order::find($id);

            $validator = Validator::make($request->all(), [
                'product_id' => ['required'],
                'quantity' => ['required', 'numeric'],
            ]);

            if ($validator->fails()) return $helper->responseError($validator->errors(), 400);

            if(Product::find($request->product_id) == null) return $helper->responseError('You input the wrong product ID!',400);

            if($request->quantity * Product::find($request->product_id)->price > 999999) return $helper->responseError('Total price maximum price is 999.999',400);

            $data->update([
                'user_id' => Auth::user()->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'total_price' => $request->quantity * Product::find($request->product_id)->price,
                'order_date' => date('Y-m-d H:i:s'),
            ]);

            return $helper->responseMessageData('Order updated successfully', $data);
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
            $data = Order::find($id);

            if(empty($data)) return $helper->responseError('Order was not found', 404);
            if($data->user_id != Auth::user()->id) return $helper->responseError('This order not belong to current authenticated user', 400);

            $data->delete();
            return $helper->responseMessageData('Order deleted successfully', $data);
        } catch (\Throwable $th) {
            return $helper->responseError($th->getMessage(), 400);
        }
    }
}
