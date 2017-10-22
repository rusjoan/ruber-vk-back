<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use VK\VK;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        \Log::debug(__METHOD__ . ' req input', $request->input());

        $loadParams = [
            'group_id' => (int) $request->get('group_id', 0),
            'access_token' => Session::get('access_token'),
        ];

        return view('home', compact('loadParams'));
    }

    public function product($id) {
        $product = \DB::table('products')->where('id', $id)->first();
        if (!$product) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Product not found');
        }

        return view('deeplink', compact('product'));
    }
}
