<?php

namespace App\Http\Controllers;

// モデル名をつけてコントーローラーを作成したのでPostモデルのuse宣言が入ってる
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->get();  // 降順で取得
        // $posts=Post::all();  // モデルに紐づいた = postsテーブルの全データを取得し変数に代入
        $user = auth()->user();  // ログイン中のユーザー情報を変数に代入
        return view('post.index', compact('posts', 'user'));  // view('ルート名', compact(変数名）)：表示する画面に変数を受け渡す
    }

    /**
     * Show the form for creating a new resource.
     */
    // resources/views/postの中のcreate.blade.phpファイルを使って、ブラウザに返答する画面を作成するという意味
    public function create()
    {
        return view('post.create');   // 引数にはフォルダ名.ファイル名
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // バリデーションチェック 連想配列で記載し各ルールは | で区切る
        $inputs = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required|max:1000',
            'image' => 'image|max:1024'
        ]);

        $post = new Post();  // 新しいPostインスタンスの作成→ここに投稿内容を入れデータベースに保存
        $post->title = $request->title;  // $request->titleはフォームから投稿された件名
        $post->body = $request->body;
        $post->user_id = auth()->user()->id;  // ログインしているユーザーのidを投稿者のidに入れる
        // 画像が投稿された時の処理
        if (request('image')) {
            $original = request()->file('image')->getClientOriginalName();  // 元々のファイル名を取得し変数に代入
            $name = date('Ymd_His') . '_' . $original;  // 日時追加
            request()->file('image')->move('storage/images', $name);  // move('保存場所', ファイル名)指定した名前で指定した場所に保存
            $post->image = $name;  // postsテーブルのimageカラムに変数名で保存
        }
        $post->save();  // 新しく作成したインスタンスをデータベースに保存
        return redirect()->route('post.create')->with('message', '投稿を作成しました');  // ルート名がpost.indexのページにリダイレクト
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('post.show', compact('post'));  // 実際に渡されているのは$post->id
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return view('post.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $inputs = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required|max:1000',
            'image' => 'image|max:1024'
        ]);

        $post->title = $inputs['title'];
        $post->body = $inputs['body'];

        if (request('image')) {
            $original = request()->file('image')->getClientOriginalName();
            $name = date('Ymd_His') . '_' . $original;
            $file = request()->file('image')->move('storage/images', $name);
            $post->image = $name;
        }

        $post->save();

        return redirect()->route('post.show', $post)->with('message', '投稿を更新しました');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('post.index')->with('message', '投稿を削除しました');
    }
}
