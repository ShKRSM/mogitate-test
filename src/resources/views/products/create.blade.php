{{-- resources/views/products/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="product-create-container">
    <div class="product-create-header">
        <h1>商品登録</h1>
    </div>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="product-create-form">
        @csrf

        <div class="form-group">
            <label for="name">商品名 <span class="required-badge">必須</span></label>
            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="商品名を入力" value="{{ old('name') }}">
            @error('name')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="price">値段 <span class="required-badge">必須</span></label>
            <input type="number" id="price" name="price" class="form-control @error('price') is-invalid @enderror" placeholder="値段を入力" value="{{ old('price') }}">
            @error('price')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="image">商品画像 <span class="required-badge">必須</span></label>
            <div class="image-upload-wrapper">
                <input type="file" id="image" name="image" class="form-control-file @error('image') is-invalid @enderror" accept=".png,.jpeg" onchange="previewImage(this);">
                <label for="image" class="file-label">ファイルを選択</label>
            </div>
            <img id="image-preview" src="#" alt="画像プレビュー" style="display:none;"/>
            @error('image')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="season">季節 <span class="required-badge">必須</span> <span class="multiple-badge">複数選択可</span></label>
            <div class="season-checkboxes">
                @foreach(['春', '夏', '秋', '冬'] as $season)
                    <div class="checkbox-wrapper">
                        <input type="checkbox" id="season-{{ $season }}" name="season[]" value="{{ $season }}" {{ (is_array(old('season')) && in_array($season, old('season'))) ? 'checked' : '' }}>
                        <label for="season-{{ $season }}">{{ $season }}</label>
                    </div>
                @endforeach
            </div>
            @error('season')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">商品説明 <span class="required-badge">必須</span></label>
            <textarea id="description" name="description" rows="5" class="form-control @error('description') is-invalid @enderror" placeholder="商品の説明を入力">{{ old('description') }}</textarea>
            @error('description')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('products.index') }}" class="back-btn">戻る</a>
            <button type="submit" class="submit-btn">登録</button>
        </div>
    </form>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('image-preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection 