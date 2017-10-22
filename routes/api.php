<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('/shop/{owner_id}/products', function ($owner_id, Request $request) {
    $items = DB::table('products')->where('owner_id', $owner_id)->get();
    $items = collect($items)->map(function ($item) {
        $item->price = $item->price ? json_decode($item->price)->text : null;
        $item->category = $item->category ? json_decode($item->category)->name : null;
        return $item;
    });
    return compact('items');
});

Route::get('/product/{id}', function ($id, Request $request) {
    $product = (array) DB::table('products')
        ->where('id', $id)
        ->first();
    $product['price'] = json_decode($product['price'])->text;
    $product['category'] = json_decode($product['category'])->name;

    return compact('product');
});

Route::get('/shop/{owner_id}/models/{product_id}', function ($owner_id, $product_id, Request $request) {
    return (array) DB::table('products')
        ->where('owner_id', $owner_id)
        ->where('product_id', $product_id)
        ->first();
});

Route::post('/shop/{owner_id}/models/{product_id}', function ($owner_id, $product_id, Request $request) {
    $request->validate([
        'model' => 'required|file',
    ]);

    $name = "model$owner_id-$product_id.zip";
    $request->file('model')->storeAs("public", $name);

    DB::table('products')->insert([
        'owner_id' => $owner_id,
        'product_id' => $product_id,
        'model' => asset("storage/$name"),
    ]);

    return (array) DB::table('products')
        ->where('owner_id', $owner_id)
        ->where('product_id', $product_id)
        ->first();
});

Route::post('/shop/{owner_id}/info/{product_id}', function ($owner_id, $product_id, Request $request) {
    $product = $request->input('product');

    DB::table('products')
        ->where('owner_id', $owner_id)
        ->where('product_id', $product_id)
        ->update([
            'title' => $product['title'],
            'description' => $product['description'],
            'created_at' => $product['date'],
            'category' => json_encode($product['category']),
            'thumb_photo' => $product['thumb_photo'],
            'price' => json_encode($product['price']),
            'size' => array_get($product, 'size', null),
        ]);

    return (array) DB::table('products')
        ->where('owner_id', $owner_id)
        ->where('product_id', $product_id)
        ->first();
});

Route::delete('/shop/{owner_id}/models/{product_id}', function ($owner_id, $product_id, Request $request) {
    $files = DB::table('products')
        ->where('owner_id', $owner_id)
        ->where('product_id', $product_id)
        ->delete();

    return [];
});

Route::get('test', function () {
    return (array) DB::table('products')
        ->first();
});

Route::get('shops', function () {
    return [
        "items" => [
            ["id" => 155407695,
            "title" => "Ruber Shop",
            "productCount" => 2,
            "categories" => [
                "count" => 3,
                "items" => [
                    ["id" => 123, "title" => "Мобильные телефоны", "section" => "Разное"],
                    ["id" => 124, "title" => "Овощи", "section" => "Разное"],
                    ["id" => 125, "title" => "Фрукты", "section" => "Разное"],
                ]
            ]],
            ["id" => 35,
            "title" => "Cool shop 2",
            "productCount" => 2,
            "categories" => [
                "count" => 3,
                "items" => [
                    ["id" => 127, "title" => "Мобильные телефоны", "section" => "Разное"],
                    ["id" => 128, "title" => "Овощи", "section" => "Разное"],
                    ["id" => 129, "title" => "Фрукты", "section" => "Разное"],
                ]
            ]]
        ]
    ];
});