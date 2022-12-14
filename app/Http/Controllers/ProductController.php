<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\sliderProduct;
use App\Models\cart;
use App\Models\order;

use Session;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    function index()
    {
        // return sliderproduct::all();
        $data = sliderproduct::all();
        return view('product',['products'=>$data]);
    }

    function detail($id)
    {
        $data = sliderproduct::find($id);
        return view('detail',['product' => $data]);
    }

    function addToCart(Request $req)
    {
        if($req->session()->has('user'))
        {
        $cart = new cart;
        $cart->user_id=$req->session()->get('user')['id'];
        $cart->product_id=$req->product_id;
        $cart->save();
        return redirect('/');
    }
    else{
        return redirect('/login');
    }
    }
    static function cartItem()
    {
        $userId=Session::get('user')['id'];
        return cart::where('user_id',$userId)->count();
    }

    function cartList()
    {
        $userId=Session::get('user')['id'];
      $products = DB::table('carts')
      ->join('sliderproducts','carts.product_id','=','sliderproducts.id')
      ->where('carts.user_id',$userId)
      ->select('sliderproducts.*','carts.id as cart_id')
      ->get();

      return view('cartlist',['products'=>$products]);
    }

    function removeCart($id)
    {
        cart::destroy($id);
        return redirect('cartlist');
    }
    
    function orderNow()
    {

          $userId=Session::get('user')['id'];
    $total =  $products = DB::table('carts')
      ->join('sliderproducts','carts.product_id','=','sliderproducts.id')
      ->where('carts.user_id',$userId)
      ->select('sliderproducts.*','carts.id as cart_id')
      ->sum('sliderproducts.price');

      return view('ordernow',['total'=>$total]);

    }

    function orderPlace(Request $req)
    {
        $userId=Session::get('user')['id'];
         $allCart=cart::where('user_id',$userId)->get();
        foreach($allCart as $cart)
        {
            $order = new order;
            $order->product_id=$cart['product_id'];
            $order->user_id=$cart['user_id'];
            $order->status="pending";
            $order->payment_method=$req->payment;
            $order->payment_status="pending";
            $order->address=$req->address;
            $order->save();
            cart::where('user_id',$userId)->delete();

        }
         $req->input();
         return redirect('/');
        

    }
    function myOrders()
    {
    $userId=Session::get('user')['id'];
     $orders = DB::table('orders')
      ->join('sliderproducts','orders.product_id','=','sliderproducts.id')
      ->where('orders.user_id',$userId)
      ->get();

      return view('myorders',['orders'=>$orders]);
    }

    function search(Request $req)
    {
        
         $data = sliderproduct::where('name','like','%'.$req->input('query').'%')->get();
         return view('search',['products'=>$data]);
    }
}
