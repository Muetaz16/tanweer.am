@extends('layouts.public')

@section('title', $post->title)

@section('content')
    <article class="tw-wrap tw-article" style="padding: 2.5rem 0 4rem;">
        <header>
            <span class="tw-pill">{{ $post->typeLabel() }}</span>
            <h1>{{ $post->title }}</h1>
            <p class="tw-muted">
                نُشر {{ optional($post->published_at)->translatedFormat('d F Y') }}
            </p>
        </header>

        @if ($post->cover_image)
            <div class="tw-card" style="margin-bottom: 1.5rem;">
                <div class="tw-card-media" style="aspect-ratio: 16/9;">
                    <img src="{{ asset('storage/'.$post->cover_image) }}" alt="">
                </div>
            </div>
        @endif

        @if ($post->supportsVideo() && $post->videoEmbedSrc())
            <div class="tw-video">
                <iframe src="{{ $post->videoEmbedSrc() }}" title="فيديو" allowfullscreen loading="lazy"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"></iframe>
            </div>
        @endif

        @if ($post->excerpt)
            <p style="font-size: 1.15rem; color: var(--tw-muted); margin-bottom: 1.5rem;">{{ $post->excerpt }}</p>
        @endif

        <div class="tw-prose">
            {!! nl2br(e($post->body)) !!}
        </div>

        @if ($related->isNotEmpty())
            <div class="tw-section-title" style="margin-top: 3rem;">
                <h2>ذات الصلة</h2>
            </div>
            <div class="tw-grid tw-grid-2">
                @foreach ($related as $r)
                    <article class="tw-card">
                        <a href="{{ route('posts.show', $r->slug) }}">
                            <div class="tw-card-body">
                                <span class="tw-pill">{{ $r->typeLabel() }}</span>
                                <h3>{{ $r->title }}</h3>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>
        @endif
    </article>
@endsection
