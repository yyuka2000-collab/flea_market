<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品の出品 | COACHTECH</title>
    <link rel="stylesheet" href="{{ asset('css/sell.css') }}">
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

    <main class="sell">
        <div class="sell__container">
            <h1 class="sell__title">商品の出品</h1>

            <form action="{{ route('exhibition.store') }}" method="POST" enctype="multipart/form-data" class="sell__form">
                @csrf

                {{-- 商品画像 --}}
                <section class="sell__section sell__section--image">
                    <h2 class="sell__section-label">商品画像</h2>
                    <div class="sell__image-upload" id="imageUploadArea">
                        <input
                            type="file"
                            id="productImage"
                            name="image"
                            accept=".jpeg,.jpg,.png"
                            class="sell__image-input"
                        >
                        <div class="sell__image-placeholder" id="imagePlaceholder">
                            <label for="productImage" class="sell__image-btn">画像を選択する</label>
                        </div>
                        <div class="sell__image-preview" id="imagePreview" style="display:none;">
                            <img id="previewImg" src="" alt="プレビュー">
                            <label for="productImage" class="sell__image-reselect">画像を変更する</label>
                        </div>
                    </div>
                    @error('image')
                        <p class="sell__error">{{ $message }}</p>
                    @enderror
                </section>

                {{-- 商品の詳細 --}}
                <section class="sell__section">
                    <h2 class="sell__section-title">商品の詳細</h2>
                    <div class="sell__section-divider"></div>

                    {{-- カテゴリー --}}
                    <div class="sell__field">
                        <label class="sell__label">カテゴリー</label>
                        <div class="sell__categories">
                            @foreach ($categories as $category)
                                <label class="sell__category-tag">
                                    <input
                                        type="checkbox"
                                        name="category_ids[]"
                                        value="{{ $category->id }}"
                                        class="sell__category-checkbox"
                                        {{ in_array($category->id, old('category_ids', [])) ? 'checked' : '' }}
                                    >
                                    <span class="sell__category-label">{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('category_ids')
                            <p class="sell__error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 商品の状態 --}}
                    <div class="sell__field">
                        <label class="sell__label" for="condition">商品の状態</label>
                        <div class="sell__select-wrap">
                            <select name="condition" id="condition" class="sell__select">
                                <option value="" disabled {{ old('condition') ? '' : 'selected' }}>選択してください</option>
                                <option value="良好" {{ old('condition') === '良好' ? 'selected' : '' }}>良好</option>
                                <option value="目立った傷や汚れなし" {{ old('condition') === '目立った傷や汚れなし' ? 'selected' : '' }}>目立った傷や汚れなし</option>
                                <option value="やや傷や汚れあり" {{ old('condition') === 'やや傷や汚れあり' ? 'selected' : '' }}>やや傷や汚れあり</option>
                                <option value="状態が悪い" {{ old('condition') === '状態が悪い' ? 'selected' : '' }}>状態が悪い</option>
                            </select>
                        </div>
                        @error('condition')
                            <p class="sell__error">{{ $message }}</p>
                        @enderror
                    </div>
                </section>

                {{-- 商品名と説明 --}}
                <section class="sell__section">
                    <h2 class="sell__section-title">商品名と説明</h2>
                    <div class="sell__section-divider"></div>

                    {{-- 商品名 --}}
                    <div class="sell__field">
                        <label class="sell__label" for="name">商品名</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="sell__input"
                            value="{{ old('name') }}"
                        >
                        @error('name')
                            <p class="sell__error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ブランド名 --}}
                    <div class="sell__field">
                        <label class="sell__label" for="brand_name">ブランド名</label>
                        <input
                            type="text"
                            id="brand_name"
                            name="brand_name"
                            class="sell__input"
                            value="{{ old('brand_name') }}"
                        >
                        @error('brand_name')
                            <p class="sell__error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 商品の説明 --}}
                    <div class="sell__field">
                        <label class="sell__label" for="description">商品の説明</label>
                        <textarea
                            id="description"
                            name="description"
                            class="sell__textarea"
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <p class="sell__error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 販売価格 --}}
                    <div class="sell__field">
                        <label class="sell__label" for="price">販売価格</label>
                        <div class="sell__price-wrap">
                            <span class="sell__price-symbol">¥</span>
                            <input
                                type="text"
                                id="price"
                                name="price"
                                class="sell__input sell__input--price"
                                value="{{ old('price') }}"
                            >
                        </div>
                        @error('price')
                            <p class="sell__error">{{ $message }}</p>
                        @enderror
                    </div>
                </section>

                <div class="sell__submit-wrap">
                    <button type="submit" class="sell__submit-btn">出品する</button>
                </div>

            </form>
        </div>
    </main>

    <script>
        // 画像プレビュー
        const input = document.getElementById('productImage');
        const placeholder = document.getElementById('imagePlaceholder');
        const preview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');

        input.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function (e) {
                previewImg.src = e.target.result;
                placeholder.style.display = 'none';
                preview.style.display = 'flex';
            };
            reader.readAsDataURL(file);
        });

        // カテゴリーチェックボックスのトグル
        document.querySelectorAll('.sell__category-checkbox').forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                const label = this.closest('.sell__category-tag');
                if (this.checked) {
                    label.classList.add('sell__category-tag--active');
                } else {
                    label.classList.remove('sell__category-tag--active');
                }
            });
            // ページロード時の初期状態
            if (checkbox.checked) {
                checkbox.closest('.sell__category-tag').classList.add('sell__category-tag--active');
            }
        });
    </script>

</body>
</html>