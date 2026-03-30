<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>マイページ | COACHTECH</title>
    <link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
</head>
<body>

    <header class="header">
        <a href="/" class="header__logo">
            <img src="{{ asset('img/COACHTECH_logo.png') }}" alt="COACHTECH">
        </a>
        <div class="header__search">
            <input type="text" placeholder="なにをお探しですか？">
        </div>
        <nav class="header__nav">
            <form action="{{ route('logout') }}" method="POST" class="header__logout-form">
                @csrf
                <button type="submit" class="header__nav-link">ログアウト</button>
            </form>
            <a href="{{ route('mypage.index') }}" class="header__nav-link">マイページ</a>
            <a href="{{ route('exhibition.index') }}" class="header__btn-sell">出品</a>
        </nav>
    </header>

    <main class="mypage">

        {{-- プロフィールセクション --}}
        <section class="profile">
            <div class="profile__inner">
                <div class="profile__avatar">
                    @if ($user->profile && $user->profile->image_path)
                        <img src="{{ asset('storage/' . $user->profile->image_path) }}" alt="{{ $user->name }}">
                    @else
                        <div class="profile__avatar-placeholder"></div>
                    @endif
                </div>
                <div class="profile__info">
                    <p class="profile__name">{{ $user->name }}</p>
                </div>
                <div class="profile__actions">
                    <a href="{{ route('mypage.profile') }}" class="profile__edit-btn">プロフィールを編集</a>
                </div>
            </div>
        </section>

        {{-- タブセクション --}}
        <div class="tab-bar">
            <div class="tab-bar__inner">
                <a href="{{ route('mypage.index', ['tab' => 'sell']) }}"
                   class="tab-bar__item {{ $tab === 'sell' ? 'tab-bar__item--active' : '' }}">
                    出品した商品
                </a>
                <a href="{{ route('mypage.index', ['tab' => 'buy']) }}"
                   class="tab-bar__item {{ $tab === 'buy' ? 'tab-bar__item--active' : '' }}">
                    購入した商品
                </a>
            </div>
        </div>

        {{-- 商品グリッドセクション --}}
        <section class="product-grid-section">
            <div class="product-grid">
                @forelse ($products as $product)
                    <a href="{{ route('item.show', $product->id) }}" class="product-card">
                        <div class="product-card__image-wrap">
                            @if ($product->image_path)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-card__image">
                            @else
                                <div class="product-card__image-placeholder">商品画像</div>
                            @endif
                        </div>
                        <p class="product-card__name">{{ $product->name }}</p>
                    </a>
                @empty
                    <p class="product-grid__empty">商品がありません。</p>
                @endforelse
            </div>
        </section>

    </main>

</body>
</html>