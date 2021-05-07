<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use PHPUnit\Exception;


/**
 * @group Products management
 *
 * APIs for managing users shopping cart
 */
class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     *
     *This endpoint allows you to get all products
     * @return \Illuminate\Http\Response
     *@response[
    *{
    *"id": 1,
    *"name": "Iphone 12",
   *"description": "Apple cell phone",
    *"price": "199.00",
    *"slug": "iphone-12",
    *"created_at": "2021-05-03T20:21:21.000000Z",
    *"updated_at": null
    *},
    *{
    *"id": 2,
    *"name": "Samsung s9",
    *"description": "Best phone from Samsung",
    *"price": "266.00",
    *"slug": "samsung-s9",
    *"created_at": "2021-05-03T20:22:54.000000Z",
    *"updated_at": null
    *},
    *{
    *"id": 3,
    *"name": "Nokia",
    *"description": "Nokia cell phone",
    *"price": "156.00",
    *"slug": "nokia",
    *"created_at": "2021-05-03T20:22:49.000000Z",
    *"updated_at": "2021-05-03T20:22:50.000000Z"
    *}
    *]
     */

    public function index()
    {
        return Product::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created product in storage.
     *
     *@queryParam name required Name of product
     *@queryParam slug required Url of product unique
     *@queryParam price required Price of product
     * This endpoint lets you create a new item.
     * @authenticated
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'price' => 'required',
        ]);
        return Product::create($request->all());
    }

    /**
     * Display the specified product by id.
     * @urlParam id integer required The ID of the product.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @response
     * {
     *"id": 1,
     *"name": "Iphone 12",
     *"description": "Apple cell phone",
     *"price": "199.00",
     *"slug": "iphone-12",
     *"created_at": "2021-05-03T20:21:21.000000Z",
     *"updated_at": null
     *}
     *
     *
     *
     *
     *
     */
    public function show($id)
    {
        try {
            return Product::findOrFail($id);
        } catch (\Exception $e) {
            abort(404);
        }
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
     * Update the specified product in storage.
     *@queryParam name required Name of product
     *@queryParam slug required Url of product unique
     *@queryParam price required Price of product
     * @response
     * @authenticated
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        try {
            $product = Product::findOrFail($id);
        } catch (\Exception $exception){
            return response("Product with id $id not exist ", 404);
        }
        $product->update($request->all());
        return response("Product with id $id was updated", 200);
    }

    /**
     * Delete product.
     * @urlParam id integer required ID of product
     * @response
     * {
     * "Product"
     * }
     * @authenticated
     * @param  int  $id
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
        } catch (\Exception $exception){
            return response("Product with id $id not exist ", 404);
        }
       Product::destroy($product->id);
        return response('Product with id $id was deleted' , 200);

    }
    /**
     * Search for products by name.
     * @urlParam name string required Field to find product in db by full or part name.
     * @param  string|int  $name
     * @return \Illuminate\Http\Response
     * @response
     * [
     *{
     *"id": 1,
     *"name": "Iphone 12",
     *"description": "Apple cell phone",
     *"price": "199.00",
     *"slug": "iphone-12",
     *"created_at": "2021-05-03T20:21:21.000000Z",
     *"updated_at": null
     *}
     *]
     */
    public function search($name)
    {
        $result = Product::where('name', 'like', '%'.$name.'%')->get();
        return response($result, 200);
    }
}
