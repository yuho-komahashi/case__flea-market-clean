<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Order;
use App\Models\Review;
use App\Mail\TradeCompletedMail;

class TradingController extends Controller
{
    public function show($orderId)
    {
        $order = Order::with([
            'item',
            'buyer.profile',
            'item.seller.profile'
        ])->findOrFail($orderId);

        $me = Auth::user();

        //相手ユーザー（出品者or購入者）
        $partner = $order->buyer_id === $me->id
            ? $order->item->seller //出品者
            : $order->buyer;       //購入者

        //商品
        $item = $order->item;

        //メッセージ一覧
        $messages = $order->messages()->orderBy('created_at')->get();

        //他の取引（サイドバー）※取引終了も含む
        $otherOrders = Order::where(function($query) use($me){
            $query->where('buyer_id', $me->id)
                ->orWhereHas('item', function($q) use($me){
                    $q->where('seller_id', $me->id);
            });
        })
            ->where('id', '!=', $order->id)
            ->with('item')
            ->get();

        $messageDraft = session("message_draft_{$order->id}");//入力メッセージをセッションに保存

        return view('trading.chat',compact(
            'order',
            'me',
            'partner',
            'item',
            'messages',
            'otherOrders',
            'messageDraft'
            ));
    }

    //取引完了アクション（orderのstatusをcompletedにする）
    public function complete(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        // 購入者だけが完了できる
        if (auth()->id() !== $order->buyer_id) {
            abort(403);
        }

        $order->update([
            'status' => 'completed',
        ]);

        // メール送信（出品者へ）
        Mail::to($order->item->seller->email)
            ->send(new TradeCompletedMail($order));

        session()->flash('completed', true);
        return redirect()->route('trading.show', $order->id)->with('completed', true);

    }

    //評価用モーダル送信
    public function rating(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        $me = auth()->user();

        $request->validate([
            'score' => 'required|integer|min:1|max:5',
        ]);

        //reviewer(評価する側)
        $reviewerId = $me->id;

        //reviewee(評価される側)
        //自分が購入者なら相手は出品者
        //自分が出品者なら相手は購入者
        $revieweeId = ($order->buyer_id === $me->id)
            ? $order->item->seller_id
            : $order->buyer_id;

        Review::create([
            'reviewer_id' => $reviewerId,
            'reviewee_id' => $revieweeId,
            'item_id' => $order->item_id,
            'score' => $request->score,
        ]);

        return redirect()->route('items.index');
    }

}
