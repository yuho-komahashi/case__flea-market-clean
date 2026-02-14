<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Order;
use App\Models\Profile;
use App\Models\Message;
use App\Http\Requests\ProfileRequest;

class MypageController extends Controller
{
    //新規プロフィール登録画面表示
    public function create()
    {
        $user = auth()->user();

        return view('mypage.profile',[
            'mode'=> 'create',
            'user'=>$user,
            'profile'=>null
        ]);
    }

    //新規プロフィール登録
    public function store(ProfileRequest $request)
    {
        $user = Auth::user();

        $path = null;
        if($request->hasFile('profile_image')){
            $path = $request->file('profile_image')->store('public/images/user_image');
        }

        $user->name = $request->input('name');
        $user->save();

        $profile = new Profile();
        $profile->user_id = $user->id;
        $profile->profile_image = $path ? basename($path):null;
        $profile->postcode = $request->input('postcode');
        $profile->address = $request->input('address');
        $profile->building = $request->input('building');
        $profile->save();

        return redirect()->route('items.index', ['tab' => 'mylist']);
    }

    //マイページ画面表示
    public function show(Request $request)
    {
        $user = Auth::user();
        $page = $request->get('page','sell');

        //最初に未読メッセージ総数を計算
        $tradingItemIds = Order::whereIn('status', ['paid', 'trading'])
            ->where(function($query) use($user){
                $query->where('buyer_id', $user->id)
                    ->orWhereHas('item', function($q) use($user){
                        $q->where('seller_id', $user->id);
                    });
            })
            ->pluck('item_id');

        //未読メッセージ総数
        $totalUnread = Message::whereIn('item_id', $tradingItemIds)
                ->where('is_read', false)
                ->where('user_id', '!=', $user->id)
                ->count();

        //タブごとの処理
        if($page === 'buy'){
            $orders = Order::where('buyer_id',$user->id)
                ->with('item')
                ->latest()
                ->get();

            //購入した商品のsold判定
            foreach($orders as $order){
                $order->item->is_sold = $order->item->item_status === 'sold'
                    || in_array($order->status,['paid','trading']);
            }

            return view('mypage.mypage',compact('orders','page','user','totalUnread'));
        }

        if($page === 'sell'){
            $items = Item::where('seller_id',$user->id)
                ->with('order')//追加
                ->latest()
                ->get();

            foreach($items as $item){
                $item->is_sold = $item->item_status === 'sold'
                    || ($item->order && in_array($item->order->status,['paid','trading']));
            }

            return view('mypage.mypage',compact('items','page','user','totalUnread'));
        }

        if($page === 'trading'){
            $tradings = Order::whereIn('status', ['paid', 'trading'])
                ->where(function($query) use($user){
                    //自分が購入者の場合
                    $query->where('buyer_id', $user->id)
                        //自分が出品者の場合は、OrderにsellerIdがないので、item経由で辿る
                        ->orWhereHas('item',function($q) use ($user){
                            $q->where('seller_id', $user->id);
                        });
                })
                ->with('item')
                ->withMax('messages', 'created_at')
                ->orderByDesc('messages_max_created_at')
                ->get();

            //商品ごとの未読数を取得
            foreach($tradings as $trading){
                $trading->unread_count = Message::where('item_id', $trading->item_id)
                    ->where('is_read', false)
                    ->where('user_id', '!=', $user->id)
                    ->count();
            }

            return view('mypage.mypage',compact('tradings','page','user','totalUnread'));
        }

        return redirect()->route('mypage.show');
    }

    //プロフィール編集画面表示
    public function edit()
    {
        $user = Auth::user();
        $profile = Profile::where('user_id', $user->id)->firstOrFail();

        return view('mypage.profile',[
            'mode' => 'edit',
            'user' => $user,
            'profile' => $profile
        ]);
    }

    //プロフィール編集画面更新
    public function update(ProfileRequest $request)
    {
        $user_id = Auth::id();
        $profile = Profile::where('user_id',$user_id)->with('user')->firstOrFail();

        if($request->hasFile('profile_image')){
            $path = $request->file('profile_image')->store('public/images/user_image');
            $profile->profile_image = basename($path);
        }

        $user = Auth::user();
        $user->name = $request->name;
        $user->save();

        $profile->postcode = $request->postcode;
        $profile->address = $request->address;
        $profile->building = $request->building;
        $profile->save();

        return redirect()->route('mypage.show', ['page' => 'sell']);
    }

}

