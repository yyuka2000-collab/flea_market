<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>プロフィール設定 | COACHTECH</title>
    <link rel="stylesheet" href="{{ asset('css/mypage-profile.css') }}">
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

    <main class="main">
        <h1 class="main__title">プロフィール設定</h1>

        <form id="profile-form" class="profile-form" action="{{ route('mypage.profile.update') }}" method="POST" enctype="multipart/form-data" novalidate >
            @csrf

            <!-- プロフィール画像 -->
            <div class="profile-form__avatar">
                <div class="profile-form__avatar-circle">
                    @if (!empty($user->profile->image_path))
                        <img id="avatar-preview" src="{{ asset('storage/' . $user->profile->image_path) }}" alt="プロフィール画像" >
                    @else
                        <svg id="avatar-placeholder" class="profile-form__avatar-placeholder" viewBox="0 0 48 48" fill="none">
                            <circle cx="24" cy="18" r="9" fill="#999"/>
                            <path d="M6 42c0-9.94 8.06-18 18-18s18 8.06 18 18" fill="#999"/>
                        </svg>
                        <img id="avatar-preview" src="" alt="プロフィール画像" style="display:none;">
                    @endif
                </div>
                <button type="button" class="profile-form__btn-image" onclick="document.getElementById('avatar-input').click()">
                    画像を選択する
                </button>
                <input type="file" id="avatar-input" name="image_path" accept=".jpeg,.jpg,.png">
            </div>
            @if(isset($errors) && $errors->has('image_path'))
                <p class="profile-form__error">{{ $errors->first('image_path') }}</p>
            @endif

            <!-- ユーザー名 -->
            <div class="profile-form__group">
                <label class="profile-form__label" for="name">ユーザー名</label>
                <input class="profile-form__input {{ isset($errors) && $errors->has("name") ? "profile-form__input--error" : "" }}" type="text" id="name" name="name" maxlength="20" value="{{ old('name', $user->name ?? '') }}" >
                @if(isset($errors) && $errors->has('name'))
                    <p class="profile-form__error">{{ $message }}</p>
                @endif
            </div>

            <!-- 郵便番号 -->
            <div class="profile-form__group">
                <label class="profile-form__label" for="postal_code">郵便番号</label>
                <input class="profile-form__input {{ isset($errors) && $errors->has("postal_code") ? "profile-form__input--error" : "" }}" type="text" id="postal_code" name="postal_code" maxlength="8" placeholder="123-4567" value="{{ old('postal_code', $user->profile->postal_code ?? '') }}" >
                
@if(isset($errors) && $errors->has('postal_code'))
                    <p class="profile-form__error">{{ $message }}</p>
                @endif
            </div>

            <!-- 住所 -->
            <div class="profile-form__group">
                <label class="profile-form__label" for="address">住所</label>
                <input class="profile-form__input {{ isset($errors) && $errors->has("address") ? "profile-form__input--error" : "" }}" type="text" id="address" name="address" value="{{ old('address', $user->profile->address ?? '') }}" >
                
@if(isset($errors) && $errors->has('address'))
                    <p class="profile-form__error">{{ $message }}</p>
                @endif
            </div>

            <!-- 建物名 -->
            <div class="profile-form__group">
                <label class="profile-form__label" for="building">建物名</label>
                <input class="profile-form__input" type="text" id="building" name="building" value="{{ old('building', $user->profile->building ?? '') }}" >
                
@if(isset($errors) && $errors->has('building'))
                    <p class="profile-form__error">{{ $message }}</p>
                @endif
            </div>

            <button type="submit" class="profile-form__btn-submit">更新する</button>
        </form>
    </main>

    <script>
        // アバター画像プレビュー
        document.getElementById('avatar-input').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function (ev) {
                const preview = document.getElementById('avatar-preview');
                const placeholder = document.getElementById('avatar-placeholder');
                preview.src = ev.target.result;
                preview.style.display = 'block';
                if (placeholder) placeholder.style.display = 'none';
            };
            reader.readAsDataURL(file);
        });
    </script>

</body>
</html>