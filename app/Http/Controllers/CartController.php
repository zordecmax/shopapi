<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Auth;

/**
 * @group Shopping cart management
 *
 * APIs for managing users shopping cart
 */
class CartController extends Controller
{
    /**
     * Display a items from the user cart.
     *
     *@authenticated
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user()->id;
//        $key  = auth()->user()->getRememberToken();
//        var_dump($userCart = Redis::get('cart:'.$key));
        $userCart = Redis::get('cart:'.$user);
//        var_dump($userCart);
//        if(empty($userCart)){
//            $userCart = json_encode(['empty']);
//            Redis::set('cart:'.$user, $userCart);
//        }

//        Redis::set('cart:item', '199');
//        dd(auth()->user());
//            Redis::command('hmset', ['cart', 5, 10]);
//        Redis::publish('cart', json_encode([
//            'name' => 'Iphone 12',
//            'qty' => '2'
//        ]));
//        session(['cart' => [
//            'item' => 'Nexus 5x',
//            'qty' => '1',
//        ]]);
//        $cart = session()->all();
//        dd(session()->getId());
//        var_dump(Redis::get('cart:'.$key));
        return Redis::get('cart:'.$user);
//        return $userCart;
//        return session()->getId();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Add new product in cart.
     *
     * @authenticated
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(empty($request)) {
            return response('No data', 406);
        }
        $item = $request->all();

        $user = auth()->user()->id;

        $userCart = Redis::get('cart:'.$user);
        if(empty($userCart)){
            $userCart = json_encode([]);
            Redis::set('cart:'.$user, $userCart);
        }
        $userCart = json_decode(Redis::get('cart:'.$user));
        $userCart[] = $item;
        Redis::set('cart:'.$user, json_encode($userCart));
        return \response('ok', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update product in the cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(empty($request)){
            return response('No items to update', 406);
        }
        $qty = intval($request->qty);
        $user = auth()->user()->id;
        $userCart = Redis::get('cart:'.$user);
        $userCartArray = json_decode($userCart, true);
        if(!empty($userCartArray)){
            foreach ($userCartArray as $key => $item) {
                if($item["id"] == $id){
                    if($qty > 0){
                        $userCartArray[$key]["qty"] += $qty;
                    } else if($qty < 0) {
                        $userCartArray[$key]["qty"] = $item["qty"] + $qty;
                    }
                    if($qty == 0 || $item["qty"] < 0) {
                        unset($userCartArray[$key]);
                    }
                }
            }
        }
        Redis::set('cart:'.$user, json_encode($userCartArray));
        return \response('Qty was updated', 200);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @authenticated
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(empty($id)){
            return response('No items to delete', 406);
        }
        $user = auth()->user()->id;

        $userCart = Redis::get('cart:'.$user);
        if(empty($userCart)){
            return response('No items to delete');
        }
        $userCartArray = json_decode($userCart, true);
        if(!empty($userCartArray)){
            foreach ($userCartArray as $key => $item) {
                if($item["id"] == $id){
                    unset($userCartArray[$key]);
                }
            }
        }
        Redis::set('cart:'.$user, json_encode($userCartArray));
        return \response('ok', 200);
    }
}
