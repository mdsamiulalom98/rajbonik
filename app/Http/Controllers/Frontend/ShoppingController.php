<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\ProductVariable;
use App\Models\Product;

class ShoppingController extends Controller
{
    //wishlist store
     public function wishlist_store(Request $request)
    {
       $product = Product::select('id', 'name', 'slug', 'new_price', 'old_price', 'purchase_price', 'type', 'stock','category_id')->where(['id' => $request->id])->first();

        if ($product->type == 0) {
            $var_product = ProductVariable::where(['product_id' => $request->id])->first();
            $purchase_price = $var_product ? $var_product->purchase_price : 0;
            $old_price = $var_product ? $var_product->old_price : 0;
            $new_price = $var_product ? $var_product->new_price : 0;
            $stock = $var_product ? $var_product->stock : 0;
        } else {
            $purchase_price = $product->purchase_price;
            $old_price = $product->old_price;
            $new_price = $product->new_price;
            $stock = $product->stock;
        }

        Cart::instance('wishlist')->add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => $request->qty??1,
            'price' => $new_price,
            'weight' => 1,
            'price' => $new_price,
            'options' => [
                'slug' => $product->slug,
                'image' => $product->image->image,
                'old_price' => $old_price,
                'purchase_price' => $purchase_price,
            ],
        ]);


        $data = Cart::instance('wishlist')->content();
        return response()->json($data);
    }
    public function wishlist_show()
    {
        $data = Cart::instance('wishlist')->content();
        return view('frontEnd.layouts.pages.wishlist', compact('data'));
    }
    public function wishlist_remove(Request $request)
    {
        $remove = Cart::instance('wishlist')->update($request->id, 0);
        $data = Cart::instance('wishlist')->content();
        return view('frontEnd.layouts.ajax.wishlist_count', compact('data'));
    }
    public function wishlist_count(Request $request){
        return view('frontEnd.layouts.ajax.wishlist_count');
    }
    public function wishlist_summary(Request $request){
        return view('frontEnd.layouts.ajax.wishlist_summary');
    }



    public function addTocartGet($id, Request $request)
    {
        $qty = 1;
        $productInfo = DB::table('products')->where('id', $id)->first();
        $productImage = DB::table('productimages')->where('product_id', $id)->first();
        $cartinfo = Cart::instance('shopping')->add([
            'id' => $productInfo->id,
            'name' => $productInfo->name,
            'qty' => $qty,
            'price' => $productInfo->new_price,
            'weight' => 1,
            'options' => [
                'image' => $productImage->image,
                'old_price' => $productInfo->old_price,
                'slug' => $productInfo->slug,
                'purchase_price' => $productInfo->purchase_price,
                'category_id' => $productInfo->category_id,
                'full_pdf' => $productInfo->full_pdf,
            ]
        ]);

        // return redirect()->back();
        return response()->json($cartinfo);
    }

    public function addTocartGetQuick(Request $request){

        $productInfo = DB::table('products')->where('id', $request->id)->first();
        $productImage = DB::table('productimages')->where('product_id', $request->id)->first();
        $var_product = ProductVariable::where(['product_id' => $request->id,'size' => $request->size])->first();
        $cartinfo = Cart::instance('shopping')->add([
            'id' => $productInfo->id,
            'name' => $productInfo->name,
            'qty' => $request->qty,
            'price' => $var_product->new_price,
            'weight' => 1,
            'options' => [
                'slug' => $productInfo->slug,
                'image' => $var_product->image,
                'old_price' => $var_product->old_price,
                'purchase_price' => $var_product->purchase_price,
                'product_size' => $var_product->size,
                'type' => $productInfo->type,
                'category_id' => $productInfo->category_id,
                'free_shipping' =>  0
            ],
        ]);

        // return redirect()->back();
        return response()->json($cartinfo);
    }

    public function cart_store(Request $request)
    {
        // return $request->all();
        $product = Product::select('id', 'name', 'slug', 'new_price', 'old_price', 'purchase_price', 'type', 'stock','category_id')->where(['id' => $request->id])->first();

        $var_product = ProductVariable::where(['product_id' => $request->id, 'color' => $request->product_color, 'size' => $request->product_size])->first();
        // return $var_product;
        if ($product->type == 0) {
            $purchase_price = $var_product ? $var_product->purchase_price : 0;
            $old_price = $var_product ? $var_product->old_price : 0;
            $new_price = $var_product ? $var_product->new_price : 0;
            $stock = $var_product ? $var_product->stock : 0;
        } else {
            $purchase_price = $product->purchase_price;
            $old_price = $product->old_price;
            $new_price = $product->new_price;
            $stock = $product->stock;
        }
        $cartitem = Cart::instance('shopping')->content()->where('id', $product->id)->first();
        if ($cartitem) {
            $cart_qty = $cartitem->qty + $request->qty??1;
        } else {
            $cart_qty = $request->qty??1;
        }
        if ($stock < $cart_qty) {
            Toastr::error('Product stock limit over', 'Failed!');
            return back();
        }

        Cart::instance('shopping')->add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => $request->qty??1,
            'price' => $new_price,
            'weight' => 1,
            'options' => [
                'slug' => $product->slug,
                'image' => $product->image->image,
                'old_price' => $new_price,
                'purchase_price' => $purchase_price,
                'product_size' => $request->product_size,
                'product_color' => $request->product_color,
                'type' => $product->type,
                'category_id' => $product->category_id,
                'free_shipping' =>  0
            ],
        ]);



        Toastr::success('Product successfully add to cart', 'Success!');
        if ($request->add_cart) {
            return back();
        }
        return redirect()->route('customer.checkout');
    }
    public function campaign_stock(Request $request)
    {
        $product = ProductVariable::where(['product_id' => $request->id, 'color' => $request->color, 'size' => $request->size])->first();

        $status = $product ? true : false;
        $response = [
            'status' => $status,
            'product' => $product
        ];
        return response()->json($response);
    }
    public function cart_content(Request $request)
    {
        $data = Cart::instance('shopping')->content();
        return view('frontEnd.layouts.ajax.cart', compact('data'));
    }
    public function cart_remove(Request $request)
    {
        $remove = Cart::instance('shopping')->update($request->id, 0);
        $data = Cart::instance('shopping')->content();
        return view('frontEnd.layouts.ajax.cart', compact('data'));
    }
    public function cart_increment(Request $request)
    {
        $item = Cart::instance('shopping')->get($request->id);
        $qty = $item->qty + 1;
        $increment = Cart::instance('shopping')->update($request->id, $qty);
        $data = Cart::instance('shopping')->content();
        return view('frontEnd.layouts.ajax.cart', compact('data'));
    }
    public function cart_decrement(Request $request)
    {
        $item = Cart::instance('shopping')->get($request->id);
        $qty = $item->qty - 1;
        $decrement = Cart::instance('shopping')->update($request->id, $qty);
        $data = Cart::instance('shopping')->content();
        return view('frontEnd.layouts.ajax.cart', compact('data'));
    }
    public function cart_count(Request $request)
    {
        $data = Cart::instance('shopping')->count();
        return view('frontEnd.layouts.ajax.cart_count', compact('data'));
    }

    public function mobilecart_qty(Request $request)
    {
        $data = Cart::instance('shopping')->count();
        return view('frontEnd.layouts.ajax.mobilecart_qty', compact('data'));
    }

    public function mobile__wishlist(Request $request)
    {
        $data = Cart::instance('wishlist')->count();
        // return $data;
        return view('frontEnd.layouts.ajax.wishlist_mobile', compact('data'));
    }


    public function cart_increment_camp(Request $request)
    {
        $item = Cart::instance('shopping')->get($request->id);
        $qty = $item->qty + 1;
        $increment = Cart::instance('shopping')->update($request->id, $qty);
        $data = Cart::instance('shopping')->content();
        return view('frontEnd.layouts.ajax.cart_camp', compact('data'));
    }
    public function cart_decrement_camp(Request $request)
    {
        $item = Cart::instance('shopping')->get($request->id);
        $qty = $item->qty - 1;
        $decrement = Cart::instance('shopping')->update($request->id, $qty);
        $data = Cart::instance('shopping')->content();
        return view('frontEnd.layouts.ajax.cart_camp', compact('data'));
    }
    public function cart_content_camp(Request $request)
    {
        $data = Cart::instance('shopping')->content();
        return view('frontEnd.layouts.ajax.cart_camp', compact('data'));
    }
    public function mini_cart()
    {
        $data = Cart::instance('shopping')->content();
        return view('frontEnd.layouts.partials.mini_cart', compact('data'));
    }

    public function mini_cart_toggle()
    {
        $data = Cart::instance('shopping')->content();
        return view('frontEnd.layouts.ajax.cart_toggle_button', compact('data'));
    }

}
