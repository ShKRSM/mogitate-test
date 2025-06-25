{{-- resources/views/products/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="product-detail-container">
    <div class="product-detail-breadcrumb">
        <a href="{{ route('products.index') }}">商品一覧</a> > {{ $product->name }}
    </div>

    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" id="updateProductForm">
        @csrf
        @method('PUT')
        
        <div class="product-detail-header">
            <h1 class="product-name-header">商品詳細</h1>
        </div>

        <div class="product-detail-main-layout">
            <div class="left-column">
                <div class="product-image-section">
                    <img src="{{ asset('storage/images/' . $product->image) }}" alt="{{ $product->name }}" class="product-detail-image" id="image-preview">
                    <div class="image-upload-wrapper">
                        <input type="file" id="image" name="image" class="form-control-file @error('image') is-invalid @enderror" accept=".png,.jpeg" onchange="previewImage(this);">
                        <label for="image" class="file-label">ファイルを選択</label>
                        <span id="file-name">{{ $product->image }}</span>
                    </div>
                    @error('image')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="right-column">
                <div class="form-group">
                    <label for="name">商品名</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" class="form-control @error('name') is-invalid @enderror">
                    @error('name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="price">値段</label>
                    <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" class="form-control @error('price') is-invalid @enderror">
                    @error('price')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>季節</label>
                    <div class="season-checkboxes">
                        @foreach(['春', '夏', '秋', '冬'] as $season)
                        <div class="checkbox-wrapper">
                            <input type="checkbox" id="season-{{ $season }}" name="seasons[]" value="{{ $season }}"
                                {{ in_array($season, $productSeasonNames ?? []) ? 'checked' : '' }}>
                            <label for="season-{{ $season }}">{{ $season }}</label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group description-group">
            <label for="description">商品説明</label>
            <textarea id="description" name="description" rows="6" class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
            @error('description')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
    </form>

    <div class="form-actions-footer">
        <button type="button" class="back-btn" onclick="location.href='{{ route('products.index') }}'">戻る</button>
        <button type="submit" class="save-btn" form="updateProductForm">変更を保存</button>
        <form action="/products/{{ $product->id }}/delete" method="POST" onsubmit="return confirm('本当に削除しますか？');" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="delete-btn">
                <i class="fas fa-trash-alt"></i>
            </button>
        </form>
    </div>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('image-preview');
    const fileNameSpan = document.getElementById('file-name');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
        fileNameSpan.textContent = input.files[0].name;
    }
}
</script>
@endsection 