<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>POTZ</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @if (Route::has('login'))
        <nav class="-mx-3 flex flex-1 justify-end">
            @auth
                <a
                    href="{{ url('/dashboard') }}"
                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                >
                    最初のページ
                </a>
            @else
                <a
                    href="{{ route('login') }}"
                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                >
                    ログイン
                </a>

                @if (Route::has('register'))
                    <a
                        href="{{ route('register') }}"
                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                    >
                        登録
                    </a>
                @endif
            @endauth
        </nav>
    @endif
    </head>
    <div class="image-container">
    <img src="img/mainbg.png" alt="">
    <div class="text-overlay">
      <p class="text1">誰もひとりにしない<P>
      <P class="text1">私も誰かの力に</p>
      <p class="text2">高齢社会を楽しくする</p>
    </div>
  </div>
</section>
<h2>お知らせ</h2>
<p></p>
<h2>会員コンテンツ</h2>
   <main>
 <div class="blog_card">
     <h3>オンラインイベント作成</h3>
    <a href="event_new.php">
      <div class="pict">
        <img src="img/news1.jpg" class=blog_card_img alt="" />
      </div>
      <p>イベントを作って呼びかけよう</p>
    </a>
  </div>
  <div class="blog_card">
     <h3 >イベント一覧</h3>
    <a href="evevt_list.php">
      <div class="pict">
        <img src="img/news2.jpg" class=blog_card_img alt="" />
      </div>
      <p>オンラインイベント予定</p>
    </a>
  </div>
</html>
