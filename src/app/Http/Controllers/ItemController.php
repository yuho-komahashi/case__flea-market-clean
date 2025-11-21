<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Comment;
use App\Models\Like;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\ExhibitionRequest;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $isLoggedIn = Auth::check();
        $tab = $request->query('tab');

        if (!$tab) {
            if($isLoggedIn){
                return redirect()->route('items.index', ['tab' => 'mylist']);
            } else {
                $tab = 'recommend';
            }
        }

        $items = collect();
        $keyword = null;

        if($tab === 'recommend'){
            $items = $isLoggedIn //ログイン済の場合
                ? Item::where('seller_id', '!=', Auth::id())->get()
                : Item::all();
        } elseif ($tab === 'mylist' && $isLoggedIn) {//マイリストタブ選択かつログインの場合
            $items = Auth::user()->likedItems()->get();
        } else {
            return redirect('/');
        }

        return view('index', compact('items','tab','keyword'));
    }

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

    public function show($itemId)
    {
        $item = Item::with('categories','condition','comments.user.profile','likedByUsers')->findOrFail($itemId);
        $categories = Category::all();
        $conditions = Condition::all();

        return view('items.detail',compact('item','categories','conditions'));
    }

    public function toggleLike(Item $item)
    {
        $user = auth()->user();

        if(!$item->likedByUsers->contains($user)){
            $item->likedByUsers()->attach($user->id);
        }else{
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

    public function storeComment(CommentRequest $request, Item $item)
    {
        $item->comments()->create([
            'user_id'=> auth()->id(),
            'comment_content' => $request->comment_content,
        ]);

        return redirect()->route('items.show', ['itemId' => $item->id]);
    }

    public function create()
    {
        $categories = Category::all();
        $conditions = Condition::all();
        $selectedCategories = old('category_id', []);

        return view('items.exhibition',compact('categories','conditions','selectedCategories'));
    }

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
