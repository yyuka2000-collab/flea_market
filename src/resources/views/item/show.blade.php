<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品詳細 - COACHTECH</title>
    <link rel="stylesheet" href="{{ asset('css/item.css') }}">
</head>
<body>
    {{-- ヘッダー --}}
    <header class="header">
        <a href="{{ route('product.index') }}" class="header__logo">
            <img src="{{ asset('img/COACHTECH_logo.png') }}" alt="COACHTECH">
        </a>
        <div class="header__search">
            <form action="{{ url('/') }}" method="GET" id="search-form">
                @if(request('tab') === 'mylist')
                    <input type="hidden" name="tab" value="mylist">
                @endif
                <input
                    type="text"
                    name="keyword"
                    placeholder="なにをお探しですか？"
                    value="{{ request('keyword') }}"
                    onblur="document.getElementById('search-form').submit()"
                    onkeydown="if(event.key === 'Enter') document.getElementById('search-form').submit()"
                >
            </form>
        </div>
        <nav class="header__nav">
            @auth
                <form action="{{ route('logout') }}" method="POST" class="header__logout-form">
                    @csrf
                    <button type="submit" class="header__nav-link">ログアウト</button>
                </form>
                <a href="{{ route('mypage.index') }}" class="header__nav-link">マイページ</a>
                <a href="{{ route('exhibition.index') }}" class="header__btn-sell">出品</a>
            @else
                <a href="{{ route('login') }}" class="header__nav-link">ログイン</a>
                <a href="{{ route('mypage.index') }}" class="header__nav-link">マイページ</a>
                <a href="{{ route('exhibition.index') }}" class="header__btn-sell">出品</a>
            @endauth
        </nav>
    </header>

    {{-- メインコンテンツ --}}
    <main class="main">
        <div class="item-detail">
            {{-- 商品画像 --}}
            <div class="item-detail__image-area">
                <img
                    src="{{ $item->image_url }}"
                    alt="{{ $item->name }}"
                    class="item-detail__image"
                >
            </div>

            {{-- 商品情報 --}}
            <div class="item-detail__info">
                <h1 class="item-detail__name">{{ $item->name }}</h1>
                <p class="item-detail__brand">{{ $item->brand_name }}</p>
                <p class="item-detail__price">¥{{ number_format($item->price) }}<span class="item-detail__price-tax">（税込）</span></p>

                {{-- いいね・コメント --}}
                <div class="item-detail__actions">
                    <div class="item-detail__like">
                        @auth
                            <form action="{{ route('likes.toggle', $item->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="item-detail__like-btn">
                                    @if($isLiked)
                                        <img src="{{ asset('img/like_on.png') }}" alt="いいね済み" class="item-detail__like-icon">
                                    @else
                                        <img src="{{ asset('img/like_off.png') }}" alt="いいね" class="item-detail__like-icon">
                                    @endif
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="item-detail__like-btn">
                                <img src="{{ asset('img/like_off.png') }}" alt="いいね" class="item-detail__like-icon">
                            </a>
                        @endauth
                        <span class="item-detail__like-count">{{ $item->likes_count }}</span>
                    </div>

                    <div class="item-detail__comment-count">
                        <img src="{{ asset('img/comment.png') }}" alt="コメント" class="item-detail__comment-icon">
                        <span class="item-detail__comment-num">{{ $item->comments_count }}</span>
                    </div>
                </div>

                {{-- 購入ボタン --}}
                @if(!$item->sold_flg)
                    @auth
                        @if (Auth::id() !== $item->user_id)
                            <a href="{{ route('purchase.index', $item->id) }}" class="item-detail__buy-btn">購入手続きへ</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="item-detail__buy-btn">購入手続きへ</a>
                    @endauth
                @else
                    <button class="item-detail__buy-btn item-detail__buy-btn--sold" disabled>売り切れました</button>
                @endif

                {{-- 商品説明 --}}
                <section class="item-detail__section">
                    <h2 class="item-detail__section-title">商品説明</h2>
                    <p class="item-detail__description">{{ $item->description }}</p>
                </section>

                {{-- 商品の情報 --}}
                <section class="item-detail__section">
                    <h2 class="item-detail__section-title">商品の情報</h2>
                    <table class="item-detail__table">
                        <tr class="item-detail__table-row">
                            <th class="item-detail__table-head">カテゴリー</th>
                            <td class="item-detail__table-data">
                                <div class="item-detail__categories">
                                    @foreach($item->categories as $category)
                                        <span class="item-detail__category-tag">{{ $category->name }}</span>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        <tr class="item-detail__table-row">
                            <th class="item-detail__table-head">商品の状態</th>
                            <td class="item-detail__table-data">{{ $item->condition }}</td>
                        </tr>
                    </table>
                </section>

                {{-- コメント一覧 --}}
                <section class="item-detail__section">
                    <h2 class="item-detail__section-title">コメント（{{ $item->comments_count }}）</h2>
                    <div class="item-detail__comments">
                        @foreach($item->comments as $comment)
                            <div class="comment">
                                <div class="comment__user">
                                    <div class="comment__avatar">
                                        @if($comment->user->profile && $comment->user->profile->image_path)
                                            <img
                                                src="{{ asset('storage/' . $comment->user->profile->image_path) }}"
                                                alt="{{ $comment->user->name }}"
                                                class="comment__avatar-img"
                                            >
                                        @else
                                            <div class="comment__avatar-placeholder"></div>
                                        @endif
                                    </div>
                                    <span class="comment__username">{{ $comment->user->name }}</span>
                                </div>
                                <p class="comment__body">{{ $comment->comment }}</p>
                            </div>
                        @endforeach
                    </div>
                </section>

                {{-- コメント送信フォーム --}}
                <section class="item-detail__section">
                    <h2 class="item-detail__section-title">商品へのコメント</h2>
                    @auth
                        <form action="{{ route('comments.store', $item->id) }}" method="POST" class="comment-form">
                            @csrf
                            <textarea
                                name="comment"
                                class="comment-form__textarea {{ isset($errors) && $errors->has('comment') ? 'is-error' : '' }}"
                                rows="5"
                            >{{ old('comment') }}</textarea>
                            @if(isset($errors) && $errors->has('comment'))
                                <p class="comment-form__error">{{ $errors->first('comment') }}</p>
                            @endif
                            <button type="submit" class="comment-form__submit">コメントを送信する</button>
                        </form>
                    @else
                        <form action="{{ route('comments.store', $item->id) }}" method="POST" class="comment-form">
                            @csrf
                            <textarea
                                name="comment"
                                class="comment-form__textarea"
                                rows="5"
                            ></textarea>
                            <button type="submit" class="comment-form__submit">コメントを送信する</button>
                        </form>
                    @endauth
                </section>
            </div>
        </div>
    </main>
</body>
</html>