<!-- resources/views/layouts/app.blade.phpファイルを全体で使うという意味 -->
<x-app-layout>
  <!-- x-slot name="header"で括ったものがapp.blade.phpファイルの$headerの場所に入る -->
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      投稿の新規作成
    </h2>
    <!-- エラーメッセージの表示 -->
    <x-validation-errors class="mb-4" :errors="$errors" />
    <!-- 投稿完了メッセージの表示 -->
    <!-- @if(session('message'))
        {{session('message')}}
    @endif -->
    <x-message :message="session('message')" />
  </x-slot>

  <!-- その他の部分はapp.blade.phpファイルの$slotの場所に入る -->
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mx-4 sm:p-8">
      <!-- 二重波括弧でXSS対策 htmlspecialcharsと同じ -->
      <form method="post" action="{{route('post.store')}}" enctype="multipart/form-data">
        <!-- シーサーフ対策 ワンタイムトークンの発行 これを書かないとエラーになる -->
        @csrf
        <div class="md:flex items-center mt-8">
          <div class="w-full flex flex-col">
            <label for="title" class="font-semibold leading-none mt-4">件名</label>
            <!-- old関数でバリデーションチェックで引っかかった時に入力内容を保持する -->
            <input type="text" name="title" class="w-auto py-2 placeholder-gray-300 border border-gray-300 rounded-md" id="title" value="{{old('title')}}" placeholder="件名を入力してください">
          </div>
        </div>

        <div class="w-full flex flex-col">
          <label for="body" class="font-semibold leading-none mt-4">本文</label>
          <textarea name="body" class="w-auto py-2  placeholder-gray-300 border border-gray-300 rounded-md" id="body" cols="30" rows="10"  placeholder="本文を入力してください">{{old('body')}}</textarea>
        </div>

        <div class="w-full flex flex-col">
          <label for="image" class="font-semibold leading-none mt-4">画像 （1MBまで） </label>
          <div>
            <input id="image" type="file" name="image">
          </div>
        </div>

        <!-- components内のファイルを使うときは <x-ファイル名> とする -->
        <x-primary-button class="mt-4">
          送信する
        </x-primary-button>

      </form>
    </div>
  </div>

</x-app-layout>