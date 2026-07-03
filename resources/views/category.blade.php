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
                                <div class="tw-card-media" style="aspect-ratio: 4/3;">
                                    @if ($post->cover_image)
                                        <img src="{{ asset('storage/'.$post->cover_image) }}" alt="">
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
