{{-- resources/views/products/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="products-page-container">
    <div class="products-page-header">
        <h1>商品一覧</h1>
        @if(Route::currentRouteName() !== 'products.search')
        <a href="{{ route('products.create') }}" class="add-product-btn">
            <span class="add-product-icon">+</span>
            商品を追加
        </a>
        @endif
    </div>

    <div class="products-main-content">
        <aside class="products-sidebar">
            <form method="GET" action="{{ route('products.search') }}" class="products-controls-form">
                <label class="products-search-label" for="search-keyword">商品名で検索</label>
                <input type="text" id="search-keyword" name="keyword" value="{{ request('keyword') }}" placeholder="商品名で検索" class="search-input">
                <button type="submit" class="search-btn">検索</button>
                @if(request('keyword'))
                    <a href="{{ route('products.search', ['sort' => request('sort')]) }}" class="clear-search-btn">クリア</a>
                @endif
                <label class="sort-label">価格順で表示</label>
                <select name="sort" class="products-sort-select" onchange="this.form.submit()">
                    <option value="">価格で並べ替え</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>低い順に表示</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>高い順に表示</option>
                </select>
            </form>
            @if(request('sort'))
                <div class="sort-tag-box">
                    <span class="sort-tag-yellow">
                        @if(request('sort') == 'price_asc')
                            低い順に表示
                        @elseif(request('sort') == 'price_desc')
                            高い順に表示
                        @endif
                        <a href="{{ route('products.search', ['keyword' => request('keyword')]) }}" class="sort-tag-close-yellow" aria-label="並び替えリセット">&times;</a>
                    </span>
                </div>
                <div class="sort-divider"></div>
            @else
                <div class="sort-divider"></div>
            @endif
        </aside>
        
        <main class="products-grid-wrapper">
            <div class="products-container">
                @foreach($products as $product)
                <a href="{{ route('products.show', $product) }}" class="product-card-link">
                    <div class="product-card">
                        <img src="{{ asset('storage/images/' . $product->image) }}" alt="{{ $product->name }}">
                        <div class="product-info">
                            <span class="product-name">{{ $product->name }}</span>
                            <span class="product-price">¥{{ number_format($product->price) }}</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            
            @if($products->hasPages())
                <nav class="pagination-wrapper" aria-label="商品一覧のページネーション">
                    <ul class="pagination">
                        @if($products->onFirstPage())
                            <li class="pagination-item disabled"><span class="pagination-link">&lt;</span></li>
                        @else
                            <li class="pagination-item"><a href="{{ $products->previousPageUrl() }}" class="pagination-link">&lt;</a></li>
                        @endif
                        @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                            <li class="pagination-item {{ $page == $products->currentPage() ? 'active' : '' }}">
                                @if($page == $products->currentPage())
                                    <span class="pagination-link">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="pagination-link">{{ $page }}</a>
                                @endif
                            </li>
                        @endforeach
                        @if($products->hasMorePages())
                            <li class="pagination-item"><a href="{{ $products->nextPageUrl() }}" class="pagination-link">&gt;</a></li>
                        @else
                            <li class="pagination-item disabled"><span class="pagination-link">&gt;</span></li>
                        @endif
                    </ul>
                </nav>
            @endif
        </main>
    </div>
</div>
@endsection