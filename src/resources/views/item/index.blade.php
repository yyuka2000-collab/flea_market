<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COACHTECH フリマ</title>
    <link rel="stylesheet" href="{{ asset('css/item-list.css') }}">
</head>
<body>

    @if (session('success'))
        <div class="flash-message" id="flashMessage">
            {{ session('success') }}
        </div>
    @endif
    <header class="header">
        <a href="/" class="header__logo">
            <img src="{{ asset('img/COACHTECH_logo.png') }}" alt="COACHTECH">
        </a>
        <div class="header__search">
            <form action="{{ url('/') }}" method="GET" id="search-form">
                {{-- マイリストタブ表示中は tab パラメータを維持 --}}
                @if(request('tab') === 'mylist')
                    <input type="hidden" name="tab" value="mylist">
                @endif
                <input type="text" name="keyword" placeholder="なにをお探しですか？" value="{{ request('keyword') }}" onblur="document.getElementById('search-form').submit()" onkeydown="if(event.key === 'Enter') document.getElementById('search-form').submit()" >
            </form>
        </div>
        <nav class="header__nav">
            @auth
                <form action="{{ route('logout') }}" method="POST" class="header__logout-form">
                    @csrf
                    <button type="submit" class="header__nav-link">ログアウト</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="header__nav-link">ログイン</a>
            @endauth
            <a href="{{ route('mypage.index') }}" class="header__nav-link">マイページ</a>
            <a href="{{ route('exhibition.index') }}" class="header__btn-sell">出品</a>
        </nav>
    </header>

    <div class="tabs">
        <a href="{{ url('/') . (request('keyword') ? '?keyword=' . request('keyword') : '') }}"
            class="tabs__item {{ request('tab') !== 'mylist' ? 'tabs__item--active' : '' }}">
            おすすめ
        </a>
        <a href="{{ url('/') . '?tab=mylist' . (request('keyword') ? '&keyword=' . request('keyword') : '') }}"
            class="tabs__item {{ request('tab') === 'mylist' ? 'tabs__item--active' : '' }}">
            マイリスト
        </a>
    </div>

    <main class="container">
        @if($products->isEmpty())
            <p class="empty-state">表示できる商品がありません</p>
        @else
            <div class="item-grid">
                @foreach($products as $product)
                    <a href="{{ route('item.show', $product->id) }}" class="item-card">
                        <div class="item-card__img-wrap">
                            <img src="{{ $product->image_url }}"
                                alt="{{ $product->name }}"
                                class="item-card__img">
                            @if($product->sold_flg)
                                <div class="item-card__sold">
                                    <span>Sold</span>
                                </div>
                            @endif
                        </div>
                        <p class="item-card__name">{{ $product->name }}</p>
                    </a>
                @endforeach
            </div>
        @endif
    </main>

    <script>
        const flash = document.getElementById('flashMessage');
        if (flash) {
            setTimeout(function () {
                flash.style.opacity = '0';
                setTimeout(function () {
                    flash.remove();
                }, 500);
            }, 2000);
        }
    </script>

</body>
</html>