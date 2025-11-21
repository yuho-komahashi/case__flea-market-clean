<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Order;
use App\Models\Profile;
use App\Http\Requests\ProfileRequest;

class MypageController extends Controller
{
    public function create()
    {
        $user = auth()->user();

        return view('mypage.profile',[
            'mode'=> 'create',
            'user'=>$user,
            'profile'=>null
        ]);
    }

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

    public function show(Request $request)
    {
        $user = Auth::user();
        $page = $request->get('page','sell');

        if($page === 'buy'){
            $orders = Order::where('buyer_id',$user->id)
                            ->where('status','paid')
                            ->with('item')
                            ->latest()
                            ->get();
        
            return view('mypage.mypage',compact('orders','page','user'));
        }
        
        if($page === 'sell'){
            $items = Item::where('seller_id',$user->id)
                            ->latest()
                            ->get();
            
            return view('mypage.mypage',compact('items','page','user'));
        }

        return redirect()->route('mypage.show');
    }

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

