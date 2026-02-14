<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\Order;
use App\Http\Requests\MessageRequest;

class MessageController extends Controller
{
    //新規取引メッセージ送付（保存）
    public function store(MessageRequest $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        $filename = null;

        //画像の保存処理
        if($request->hasFile('image')){
            //アップロードされたファイル名から拡張子をのみを取得
            $ext = $request->file('image')->getClientOriginalExtension();

            //ランダムなファイル名を生成
            $filename = uniqid() . '.' . $ext;

            // storage/app/public/images/chat_image に保存
            $request->file('image')->storeAs('public/images/chat_image', $filename);
        }

        //メッセージ保存
        $order->messages()->create([
            'user_id' => Auth::id(),
            'body' => $request->message,
            'image' => $filename, // DB にはファイル名だけ保存
        ]);

        //同じチャット画面に戻る
        return redirect()->route('trading.show', $order->id);
    }

    //自分の既存メッセージ編集
    public function update (Request $request, Message $message)
    {
        //自分のメッセージ以外は編集不可
        if($message->user_id != auth()->id()){
            abort(403);
        }

        $request->validate([
            'body' => 'required|string|max:400',
        ]);

        $message->update([
            'body' => $request->body,
        ]);

        return back();
    }

    //自分の既存メッセージ削除
    public function destroy(Message $message)
    {
        //自分のメッセージ以外は削除不可
        if($message->user_id !== auth()->id()){
            abort(403);
        }

        $message->delete();

        return back();
    }
}
