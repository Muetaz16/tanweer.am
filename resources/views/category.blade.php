@extends('layouts.public')

@section('title', $categoryName)

@section('content')
    @if($type === \App\Models\Post::TYPE_REELS)
        @if ($posts->isEmpty())
            <div class="tw-wrap" style="padding-top: 2rem; padding-bottom: 3rem;">
                <p class="tw-muted">لا يوجد محتوى في هذا القسم بعد.</p>
            </div>
        @else
            <div class="tw-reels-container">
                @foreach ($posts as $post)
                    @include('components.reel-tiktok', ['post' => $post])
                @endforeach
            </div>
            
            <div class="tw-reels-pagination">
                {{ $posts->links('pagination.tanweer') }}
            </div>
        @endif
    @else
        <div class="tw-wrap" style="padding-top: 2rem; padding-bottom: 3rem;">
            <div class="tw-section-title">
                <h2>{{ $categoryName }}</h2>
            </div>

            @if ($posts->isEmpty())
                <p class="tw-muted">لا يوجد محتوى في هذا القسم بعد.</p>
            @else
                <div class="tw-grid tw-grid-3">
                    @foreach ($posts as $post)
                        <article class="tw-card">
                            <a href="{{ route('posts.show', $post->slug) }}">
                                <div class="tw-card-media" style="aspect-ratio: 4/3; position: relative;">
                                    @if ($post->thumbnailUrl())
                                        <img src="{{ $post->thumbnailUrl() }}" alt="">
                                    @endif
                                    @if ($post->images->count() > 1)
                                        <span style="position: absolute; bottom: 8px; left: 8px; background: rgba(15, 30, 23, 0.85); color: var(--tw-beige-pale); padding: 3px 9px; border-radius: 12px; font-size: 0.75rem; font-weight: 700; display: flex; align-items: center; gap: 4px; backdrop-filter: blur(4px);">
                                            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            {{ $post->images->count() }} صور
                                        </span>
                                    @endif
                                </div>
                                <div class="tw-card-body">
                                    <h3>{{ $post->title }}</h3>
                                    <p class="tw-muted">{{ $post->excerpt ? \Illuminate\Support\Str::limit($post->excerpt, 100) : \Illuminate\Support\Str::limit(strip_tags($post->body), 100) }}</p>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>

                <div style="margin-top: 3rem;">
                    {{ $posts->links('pagination.tanweer') }}
                </div>
            @endif
        </div>
    @endif
@endsection

@section('scripts')
@if($type === \App\Models\Post::TYPE_REELS)
<script src="https://www.youtube.com/iframe_api"></script>
<script>
    let players = {};
    let observer;

    // Called automatically by YouTube IFrame API when ready
    function onYouTubeIframeAPIReady() {
        initReelsObserver();
    }

    function initReelsObserver() {
        const reelItems = document.querySelectorAll('.tw-reel-tiktok');
        
        const options = {
            root: document.querySelector('.tw-reels-container'), // Use the scroll container as root
            rootMargin: '0px',
            threshold: 0.6 // 60% of the item must be visible
        };

        observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                const videoId = entry.target.dataset.videoId;
                if (!videoId) return;

                if (entry.isIntersecting) {
                    playReel(entry.target, videoId);
                } else {
                    pauseReel(videoId);
                }
            });
        }, options);

        reelItems.forEach(item => {
            observer.observe(item);
            
            // Desktop Hover Behavior
            item.addEventListener('mouseenter', () => {
                if(window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
                    playReel(item, item.dataset.videoId);
                }
            });
            item.addEventListener('mouseleave', () => {
                if(window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
                    pauseReel(item.dataset.videoId);
                }
            });
        });
    }

    function playReel(element, videoId) {
        // Pause all other active players
        Object.keys(players).forEach(id => {
            if (id !== videoId && players[id] && typeof players[id].pauseVideo === 'function') {
                players[id].pauseVideo();
            }
        });

        // Hide overlay thumb
        const thumb = element.querySelector('.tw-reel-thumb-overlay');
        if (thumb) thumb.style.opacity = '0';

        if (players[videoId]) {
            if (typeof players[videoId].playVideo === 'function') {
                players[videoId].playVideo();
            }
        } else {
            // Initialize player
            const container = element.querySelector('.tw-reel-video-target');
            if (container) {
                players[videoId] = new YT.Player(container, {
                    videoId: videoId,
                    playerVars: {
                        'autoplay': 1,
                        'controls': 1,
                        'rel': 0,
                        'playsinline': 1,
                        'mute': 1,
                        'enablejsapi': 1
                    },
                    events: {
                        'onReady': (event) => {
                            event.target.playVideo();
                        }
                    }
                });
            }
        }
    }

    function pauseReel(videoId) {
        if (players[videoId] && typeof players[videoId].pauseVideo === 'function') {
            players[videoId].pauseVideo();
        }
    }
</script>
@endif
@endsection
