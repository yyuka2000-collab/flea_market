<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>住所の変更 | COACHTECH</title>
    <link rel="stylesheet" href="{{ asset('css/address.css') }}">
</head>
<body>

<header class="header">
    <a href="/" class="header__logo">
        <img src="{{ asset('img/COACHTECH_logo.png') }}" alt="COACHTECH">
    </a>
    <div class="header__search">
        <form action="{{ url('/') }}" method="GET" id="search-form">
            <input type="text" name="keyword" placeholder="なにをお探しですか？" value="{{ request('keyword') }}">
        </form>
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

<main class="address">
    <div class="address__container">

        <h1 class="address__title">住所の変更</h1>

        <form action="{{ route('address.store', $item->id) }}" method="POST" class="address__form">
            @csrf
            <input type="hidden" name="payment_method" value="{{ session('purchase_payment_method') }}">

            <div class="address__field">
                <label for="postal_code" class="address__label">郵便番号</label>
                <input
                    type="text"
                    id="postal_code"
                    name="postal_code"
                    class="address__input @error('postal_code') is-error @enderror"
                    value="{{ old('postal_code', session('shipping_postal_code', $profile->postal_code ?? '')) }}"
                    placeholder="123-4567"
                >
                @error('postal_code')
                    <p class="address__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="address__field">
                <label for="address" class="address__label">住所</label>
                <input
                    type="text"
                    id="address"
                    name="address"
                    class="address__input @error('address') is-error @enderror"
                    value="{{ old('address', session('shipping_address', $profile->address ?? '')) }}"
                >
                @error('address')
                    <p class="address__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="address__field">
                <label for="building" class="address__label">建物名</label>
                <input
                    type="text"
                    id="building"
                    name="building"
                    class="address__input"
                    value="{{ old('building', session('shipping_building', $profile->building ?? '')) }}"
                >
            </div>

            <button type="submit" class="address__btn-submit">更新する</button>

        </form>

    </div>
</main>

</body>
</html>