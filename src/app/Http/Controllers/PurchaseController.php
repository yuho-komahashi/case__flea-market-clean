<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Order;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PurchaseController extends Controller
{
    //商品購入画面表示
    public function confirm($item_id)
    {
        $purchaseItem = Item::findOrFail($item_id);

        $user_id = Auth::id();
        $shipping_address = Profile::with('user')->where('user_id', $user_id)->firstOrFail();

        return view('purchases.purchase',compact('purchaseItem','shipping_address'));
    }

    //住所変更ページ表示
    public function editAddress($item_id)
    {
        $purchaseItem = Item::findOrFail($item_id);
        return view('purchases.address',compact('purchaseItem'));
    }

    //住所変更
    public function updateAddress(AddressRequest $request, $item_id)
    {
        session([
            'updateData'=>[
                'shipping_postcode' => $request->shipping_postcode,
                'shipping_address' => $request->shipping_address,
                'shipping_building' => $request->shipping_building,
            ]
        ]);

        return redirect()->route('purchase.confirm',['item_id'=> $item_id]);
    }

    //商品購入
    public function store(PurchaseRequest $request, $item_id)
    {
        $purchaseItem = Item::findOrFail($item_id);//1.購入される商品をDBから取得（商品対象を特定）

        Stripe::setApiKey(config('services.stripe.secret'));//2.ストライプセッション作成

        if (in_array($request->payment_method, ['card', 'konbini'])){

            $session = Session::create([ // Stripeセッション作成（決済ページを作成）
                'payment_method_types' => [$request->payment_method],
                'line_items'=>[[
                    'price_data'=>[
                        'currency'=>'jpy',
                        'product_data'=>[
                            'name'=> $purchaseItem->item_name,
                        ],
                        'unit_amount'=>$purchaseItem->price,
                    ],
                    'quantity'=>1,
                ]],
                'mode'=>'payment',
                'success_url' => url('/mypage?page=buy'),
            ]);

            $purchaseItem->item_status = 'sold';//3.1の商品ステータスを売却済に変更
            $purchaseItem->save();//4.売却済ステータスをDBに保存

            Order::create([ // 注文を保存（status = paid）
                'buyer_id'=> Auth::id(),
                'item_id'=> $item_id,
                'payment_method'=>$request->payment_method,
                'shipping_postcode'=>$request->shipping_postcode,
                'shipping_address'=>$request->shipping_address,
                'shipping_building'=>$request->shipping_building,
                'status' => 'paid',
            ]);

            return redirect($session->url);
        }
    }
}
