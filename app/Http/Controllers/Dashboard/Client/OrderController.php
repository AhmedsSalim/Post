<?php

namespace App\Http\Controllers\Dashboard\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Client $client)
    {

    $categories= Category::with('products')->get();
    $orders= Order::with('products')->paginate(5);
       return  view('dashboard.clients.orders.create',compact('client','categories','orders'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Client $client,Request $request)
    {

        $request->validate([
           'products' => 'required|array',
        //    'quantity' => 'required|array',
        ]);
            $this->attch_order($request,$client);

        session()->flash('success', __('site.added_successfully'));
        return  redirect()->route('dashboard.orders.index');


    }// end of store



    public function edit( Client $client,Order $order)
    {
        $categories= Category::with('products')->get();
        $orders= Order::with('products')->paginate(5);
        return view('dashboard.clients.orders.edit',compact('client','order','categories','orders'));
    }


    public function update(Request $request, Client $client,Order $order)
    {
        $request->validate([
            'products' => 'required|array',

         ]);
            $this->detach_order($order);

             $this->attch_order($request,$client);


         session()->flash('success', __('site.updated_successfully'));
         return  redirect()->route('dashboard.orders.index');
    }





 //// attach order
    private function attch_order(Request $request, Client $client){


        $order = $client->orders()->create([]);
        $order->products()->attach($request->products);

        $total_price =0;

        foreach($request->products  as $id=>$quantity){

            $product = Product::findOrFail($id);
            $total_price += $product->sale_price  * $quantity['quantity'] ;

            $product->update([
                'stock' => $product->stock - $quantity['quantity']
            ]);

        } ///emd of foreach

        $order->update([
            'total_price'=>   $total_price
        ]);
    } //// end attach order

    /// detach order

    private function detach_order(Order $order){
        foreach ($order->products as $product) {
            $product->update([
                'stock' => $product->stock + $product->pivot->quantity
            ]);
        }//emd of foreach

        $order->delete();
    }
    ///end detach order

}
