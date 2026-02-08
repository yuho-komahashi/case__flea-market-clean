<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Comment;
//use App\Models\Like;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\ExhibitionRequest;

class ItemController extends Controller
{
    //商品一覧表示＋検索機能
    public function index(Request $request)
    {
        $isLoggedIn = Auth::check();//ログイン状態を判定（つまり、ログインしていないときは全商品表示）

        // タブ（デフォルトは recommend）
        $tab = $request->query('tab');

        if($tab !== 'recommend' && $tab !== 'mylist'){
            $tab = $isLoggedIn ? 'mylist' : 'recommend';
        }

        // 検索キーワード
        $keyword = $request->query('keyword');

        // ベースクエリ Itemテーブルを使った空のクエリを作成
        $query = Item::query();

        // 自分の商品は除外（ログイン時のみ）
        if($isLoggedIn){
            //sellerIdがログインユーザーではない商品に絞り込みという条件をqueryに追加
            $query->where('seller_id', '!=', Auth::id());
        }

        // タブごとの絞り込み
        if($tab === 'mylist') {

            if($isLoggedIn){//ログインしている場合
                // ログインユーザーがいいねした商品のみに絞るという条件をqueryに追加
                $query->whereIn('id', function($sub){
                    $sub->select('item_id')
                    ->from('likes')
                    ->where('user_id', Auth::id());
                });

            } else {
                // 未ログイン時は空のコレクションを返す
                $items = collect();
                return view('index', compact('items','tab','keyword'));
            }
        }

        //検索
        if(!empty($keyword)){
            //keywordがあったら、keywordで絞るという条件をqueryに追加
            $query->where('item_name', 'like', "%{$keyword}%");
        }

        //最終結果
        $items = $query->get();//←ここで初めてDBにアクセスし、SQLが実行されて実際のデータが$itemsに入る

        return view('index', compact('items','tab','keyword'));
    }

    /*商品検索機能・・・indexにまとめたので不要
    public function search(Request $request)
    {
        $keyword = $request->keyword;
        $tab = $request->tab ?? 'recommend';
        $isLoggedIn = Auth::check();
        $items = collect();

        if($tab === 'recommend'){
            $query = $isLoggedIn
                ? Item::where('seller_id', '!=', Auth::id())
                : Item::query();

                if($keyword){
                    $query = $query->where('item_name', 'like', '%' . $keyword . '%');
                }

            $items = $query->get();

        } elseif ($tab === 'mylist' && $isLoggedIn){
            $items = Auth::user()->likedItems()
            ->where('item_name', 'like', '%' . $request->keyword . '%')
            ->get();

        } else {
            return redirect('/');
        }

        return view('index', compact('items','tab','keyword'));
    }
    */

    //商品詳細表示
    public function show($itemId)
    {
        $item = Item::with('categories','condition','comments.user.profile','likedByUsers')->findOrFail($itemId);
        $categories = Category::all();
        $conditions = Condition::all();

        return view('items.detail',compact('item','categories','conditions'));
    }

    //いいね増減
    public function toggleLike(Item $item)
    {
        $user = auth()->user();

        // ★ 自分の商品にはいいねできない
        if($item->seller_id === $user->id){
            return back()->with('error', '自分で出品した商品にはいいねできません');
        }

        // いいねしていない → いいねする
        if(!$item->likedByUsers->contains($user)){
            $item->likedByUsers()->attach($user->id);
        }
        // いいね済み → いいね解除
        else{
            $item->likedByUsers()->detach($user->id);
        }
        return redirect()->route('items.show', $item->id);
    }

    //購入ボタンを押したときに未ログインだった場合にログインページへ飛ばすための専用アクション
    public function redirectToLogin($itemId)
    {
        session(['after_login_redirect' => route('items.show', ['itemId' => $itemId])]);

        return redirect('/login');
    }

    //コメント保存
    public function storeComment(CommentRequest $request, Item $item)
    {
        $item->comments()->create([
            'user_id'=> auth()->id(),
            'comment_content' => $request->comment_content,
        ]);

        return redirect()->route('items.show', ['itemId' => $item->id]);
    }

    //商品出品画面表示
    public function create()
    {
        $categories = Category::all();
        $conditions = Condition::all();
        $selectedCategories = old('category_id', []);

        return view('items.exhibition',compact('categories','conditions','selectedCategories'));
    }

    //商品出品（登録）
    public function store(ExhibitionRequest $request)
    {
        $path = null;

        if($request->hasFile('item_image')){
            $path = $request->file('item_image')->store('public/images/item_image');
        }

        $item = Item::create([
            'seller_id'=> Auth::id(),
            'item_image'=> basename($path),
            'condition_id' => $request->condition_id,
            'item_name' => $request->item_name,
            'brand' => $request->brand,
            'description' => $request->description,
            'price' => $request->price,
            'item_status' => 'available',
        ]);

        $item->categories()->attach($request->category_id);

        return redirect()->route('mypage.show');
    }

}
