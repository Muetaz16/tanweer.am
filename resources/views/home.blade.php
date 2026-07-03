@extends('layouts.public')

@section('title', 'الرئيسية')

@section('content')
    <div class="tw-wrap" style="padding-top: 2rem; padding-bottom: 3rem;">
        
        {{-- Featured Section --}}
        @if ($featured->isNotEmpty())
            <div class="tw-grid tw-grid-featured">
                <div class="tw-featured-main">
                    @php $mainFeature = $featured->first(); @endphp
                    <article class="tw-card tw-card-large">
                        <a href="{{ route('posts.show', $mainFeature->slug) }}">
                            <div class="tw-card-media">
                                @if ($mainFeature->cover_image)
                                    <img src="{{ asset('storage/'.$mainFeature->cover_image) }}" alt="">
                                @endif
                            </div>
                            <div class="tw-card-overlay">
                                <span class="tw-pill">{{ $mainFeature->typeLabel() }}</span>
                                <h2>{{ $mainFeature->title }}</h2>
                            </div>
                        </a>
                    </article>
                </div>
                <div class="tw-featured-side">
                    @foreach ($featured->skip(1)->take(3) as $post)
                        <article class="tw-card tw-card-small">
                            <a href="{{ route('posts.show', $post->slug) }}">
                                <div class="tw-card-media">
                                    @if ($post->cover_image)
                                        <img src="{{ asset('storage/'.$post->cover_image) }}" alt="">
                                    @endif
                                </div>
                                <div class="tw-card-body">
                                    <h3>{{ $post->title }}</h3>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        @else
            <p class="tw-muted" style="text-align: center;">لا يوجد محتوى منشور بعد.</p>
        @endif

        <div class="tw-grid tw-grid-layout">
            <div class="tw-main-column">
                {{-- Reports Section --}}
                @if ($reportsPosts->isNotEmpty())
                    <section class="tw-category-section">
                        <div class="tw-section-title">
                            <h2>تقارير</h2>
                            <a href="{{ route('category.show', 'reports') }}">المزيد</a>
                        </div>
                        <div class="tw-grid tw-grid-2">
                            @foreach ($reportsPosts as $post)
                                <article class="tw-card">
                                    <a href="{{ route('posts.show', $post->slug) }}">
                                        <div class="tw-card-media" style="aspect-ratio: 4/3;">
                                            @if ($post->cover_image)
                                                <img src="{{ asset('storage/'.$post->cover_image) }}" alt="">
                                            @endif
                                        </div>
                                        <div class="tw-card-body">
                                            <h3>{{ $post->title }}</h3>
                                        </div>
                                    </a>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif

                {{-- Investigation & Article (Two Columns) --}}
                <div class="tw-grid tw-grid-2" style="margin-top: 2rem;">
                    @if ($investigationPosts->isNotEmpty())
                        <section class="tw-category-section">
                            <div class="tw-section-title">
                                <h2>تحقيقات</h2>
                                <a href="{{ route('category.show', 'investigation') }}">المزيد</a>
                            </div>
                            <div class="tw-grid tw-grid-1">
                                @foreach ($investigationPosts as $post)
                                    <article class="tw-card tw-card-horizontal">
                                        <a href="{{ route('posts.show', $post->slug) }}">
                                            <div class="tw-card-media" style="aspect-ratio: 1/1; width: 100px;">
                                                @if ($post->cover_image)
                                                    <img src="{{ asset('storage/'.$post->cover_image) }}" alt="">
                                                @endif
                                            </div>
                                            <div class="tw-card-body">
                                                <h3>{{ $post->title }}</h3>
                                            </div>
                                        </a>
                                    </article>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    @if ($articlePosts->isNotEmpty())
                        <section class="tw-category-section">
                            <div class="tw-section-title">
                                <h2>مقالات</h2>
                                <a href="{{ route('category.show', 'article') }}">المزيد</a>
                            </div>
                            <div class="tw-grid tw-grid-1">
                                @foreach ($articlePosts as $post)
                                    <article class="tw-card tw-card-horizontal">
                                        <a href="{{ route('posts.show', $post->slug) }}">
                                            <div class="tw-card-media" style="aspect-ratio: 1/1; width: 100px;">
                                                @if ($post->cover_image)
                                                    <img src="{{ asset('storage/'.$post->cover_image) }}" alt="">
                                                @endif
                                            </div>
                                            <div class="tw-card-body">
                                                <h3>{{ $post->title }}</h3>
                                            </div>
                                        </a>
                                    </article>
                                @endforeach
                            </div>
                        </section>
                    @endif
                </div>
            </div>

            <aside class="tw-sidebar">
                {{-- Latest News Widget --}}
                @if ($latestNews->isNotEmpty())
                    <div class="tw-widget">
                        <div class="tw-widget-title">
                            <h3>أحدث الأخبار</h3>
                        </div>
                        <ul class="tw-widget-list">
                            @foreach ($latestNews as $post)
                                <li>
                                    <a href="{{ route('posts.show', $post->slug) }}">{{ $post->title }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                {{-- Reels Widget --}}
                @if ($reelsPosts->isNotEmpty())
                    <div class="tw-widget" style="margin-top: 2rem;">
                        <div class="tw-widget-title">
                            <h3>ريلز</h3>
                        </div>
                        <div class="tw-widget-videos">
                            @foreach ($reelsPosts as $post)
                                <article class="tw-card">
                                    <a href="{{ route('posts.show', $post->slug) }}">
                                        <div class="tw-card-media" style="aspect-ratio: 16/9;">
                                            @if ($post->cover_image)
                                                <img src="{{ asset('storage/'.$post->cover_image) }}" alt="">
                                            @endif
                                            <div class="tw-play-icon">▶</div>
                                        </div>
                                        <div class="tw-card-body" style="padding: 0.5rem 0;">
                                            <h3 style="font-size: 1rem;">{{ $post->title }}</h3>
                                        </div>
                                    </a>
                                </article>
                            @endforeach
                        </div>
                    </div>
                @endif
            </aside>
        </div>
    </div>
@endsection
