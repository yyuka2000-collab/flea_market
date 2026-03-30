<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品購入 | COACHTECH</title>
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
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

<main class="purchase">
    <div class="purchase__container">

        {{-- 左カラム --}}
        <div class="purchase__left">

            {{-- 商品情報 --}}
            <div class="purchase__item">
                <div class="purchase__item-image">
                    @if($item->image_path)
                        <img src="{{ $item->image_url }}" alt="{{ $item->name }}">
                    @else
                        <span class="purchase__item-image--placeholder">商品画像</span>
                    @endif
                </div>
                <div class="purchase__item-info">
                    <p class="purchase__item-name">{{ $item->name }}</p>
                    <p class="purchase__item-price">¥ {{ number_format($item->price) }}</p>
                </div>
            </div>

            <hr class="purchase__divider">

            {{-- 支払い方法 --}}
            <div class="purchase__section">
                <h2 class="purchase__section-title">支払い方法</h2>
                <form action="{{ route('purchase.store', $item->id) }}" method="POST" id="purchase-form">
                    @csrf
                    <div class="purchase__select-wrap">
                        <select name="payment_method" id="payment_method" class="purchase__select" onchange="updatePaymentSummary(this.value)">
                            <option value="" disabled {{ !old('payment_method') && !session('purchase_payment_method') ? 'selected' : '' }}>選択してください</option>
                            <option value="convenience" {{ old('payment_method', session('purchase_payment_method')) === 'convenience' ? 'selected' : '' }}>コンビニ支払い</option>
                            <option value="card" {{ old('payment_method', session('purchase_payment_method')) === 'card' ? 'selected' : '' }}>カード支払い</option>
                        </select>
                        <span class="purchase__select-arrow">▼</span>
                    </div>
                    @if(isset($errors) && $errors->has('payment_method'))
                        <p class="purchase__error">{{ $errors->first('payment_method') }}</p>
                    @endif
                </form>
            </div>

            <hr class="purchase__divider">

            {{-- 配送先 --}}
            <div class="purchase__section">
                <div class="purchase__section-header">
                    <h2 class="purchase__section-title">配送先</h2>
                    <form action="{{ route('purchase.address', $item->id) }}" method="GET" id="address-form">
                        <input type="hidden" name="payment_method" id="hidden_payment_method" value="{{ old('payment_method', session('purchase_payment_method')) }}">
                        <button type="submit" class="purchase__link" onclick="document.getElementById('hidden_payment_method').value = document.getElementById('payment_method').value">変更する</button>
                    </form>
                </div>
                <div class="purchase__address">
                    @php
                        $shippingPostal   = session('shipping_postal_code', $profile->postal_code ?? '');
                        $shippingAddress  = session('shipping_address',     $profile->address ?? '');
                        $shippingBuilding = session('shipping_building',    $profile->building ?? '');
                    @endphp
                    @if($shippingPostal || $shippingAddress)
                        <p class="purchase__address-postal">〒 {{ $shippingPostal }}</p>
                        <p class="purchase__address-body">{{ $shippingAddress }}{{ $shippingBuilding }}</p>
                    @else
                        <p class="purchase__address-empty">住所が登録されていません</p>
                    @endif
                </div>
            </div>

            <hr class="purchase__divider">

        </div>

        {{-- 右カラム --}}
        <div class="purchase__right">
            <div class="purchase__summary">
                <div class="purchase__summary-row">
                    <span class="purchase__summary-label">商品代金</span>
                    <span class="purchase__summary-value">¥ {{ number_format($item->price) }}</span>
                </div>
                <div class="purchase__summary-row">
                    <span class="purchase__summary-label">支払い方法</span>
                        <span class="purchase__summary-value" id="summary-payment">
                            @php
                                $selectedPayment = old('payment_method', session('purchase_payment_method'));
                            @endphp
                            @if($selectedPayment === 'convenience')
                                コンビニ支払い
                            @elseif($selectedPayment === 'card')
                                カード支払い
                            @else
                                &mdash;
                            @endif
                        </span>
                </div>
            </div>

            <button type="submit" form="purchase-form" class="purchase__btn-buy">購入する</button>

            {{-- エラーメッセージ --}}
            @if(isset($errors) && $errors->has('error'))
                <p class="purchase__error purchase__error--global">{{ $errors->first() }}</p>
            @endif
        </div>

    </div>
</main>

<script>
    function updatePaymentSummary(value) {
        const el = document.getElementById('summary-payment');
        const labels = {
            'convenience': 'コンビニ支払い',
            'card': 'カード支払い',
        };
        el.textContent = labels[value] || '—';
    }
</script>

</body>
</html>