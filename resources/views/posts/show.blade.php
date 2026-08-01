@extends('layouts.public')

@section('title', $post->title)

@section('content')
    <article class="tw-wrap tw-article" style="padding: 2.5rem 0 4rem;">
        <header style="margin-bottom: 2rem;">
            <span class="tw-pill">{{ $post->typeLabel() }}</span>
            <h1 style="margin-top: 0.5rem;">{{ $post->title }}</h1>
            
            <div style="display: flex; gap: 1rem; align-items: center; margin-top: 0.5rem; flex-wrap: wrap;">
                @if ($post->author_name)
                    <span style="font-weight: 700; color: var(--tw-green-dark); font-size: 0.95rem; display: flex; align-items: center; gap: 0.35rem;">
                        ✍️ بقلم: {{ $post->author_name }}
                    </span>
                @endif
                <span class="tw-muted">
                    نُشر {{ optional($post->published_at)->translatedFormat('d F Y') }}
                </span>
            </div>
        </header>

        <!-- Infographics / Reports Multiple Images Gallery Display -->
        @if ($post->images->isNotEmpty())
            <div class="tw-infographic-gallery" style="margin-bottom: 2.5rem; background: #0f1e17; border-radius: var(--tw-radius); padding: 1.25rem; color: #fff; box-shadow: var(--tw-shadow);">
                <!-- Gallery Top Info Bar -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 0.75rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; font-weight: 700; font-size: 0.95rem; color: var(--tw-beige);">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>معرض الصور</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); padding: 0.25rem 0.85rem; border-radius: 999px; font-size: 0.85rem; font-weight: 700; color: var(--tw-beige-pale);">
                        صورة <span id="gallery_current_idx">1</span> من {{ $post->images->count() }}
                    </div>
                </div>

                <!-- Main Active Image Container -->
                <div style="position: relative; display: flex; align-items: center; justify-content: center; background: #050b08; border-radius: var(--tw-radius-sm); overflow: hidden; min-height: 380px; max-height: 75vh;">
                    <img id="gallery_main_img" src="{{ $post->images->first()->url() }}" alt="{{ $post->title }}" style="max-width: 100%; max-height: 75vh; object-fit: contain; cursor: zoom-in; transition: opacity 0.25s ease;" onclick="openLightbox()">

                    <!-- Prev/Next Controls -->
                    @if ($post->images->count() > 1)
                        <button type="button" class="gallery-nav-btn" style="right: 15px;" onclick="changeGalleryImage(-1)" aria-label="الصورة السابقة">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <button type="button" class="gallery-nav-btn" style="left: 15px;" onclick="changeGalleryImage(1)" aria-label="الصورة التالية">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                    @endif
                </div>

                <!-- Thumbnails Strip -->
                @if ($post->images->count() > 1)
                    <div class="tw-gallery-thumbs" style="display: flex; gap: 0.75rem; margin-top: 1rem; overflow-x: auto; padding-bottom: 0.5rem; scroll-behavior: smooth;">
                        @foreach ($post->images as $idx => $img)
                            <div class="gallery-thumb-item {{ $idx === 0 ? 'active' : '' }}" onclick="setGalleryImage({{ $idx }})" data-index="{{ $idx }}" data-src="{{ $img->url() }}" style="flex: 0 0 80px; height: 80px; border-radius: var(--tw-radius-sm); overflow: hidden; cursor: pointer; border: 2px solid transparent; transition: all 0.2s ease;">
                                <img src="{{ $img->url() }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Lightbox Modal -->
            <div id="infographic_lightbox" class="tw-lightbox-modal" onclick="closeLightbox(event)">
                <span class="tw-lightbox-close" onclick="closeLightbox(event)">&times;</span>
                <img id="lightbox_img" src="" alt="">
            </div>

            <style>
                .gallery-nav-btn {
                    position: absolute;
                    top: 50%;
                    transform: translateY(-50%);
                    background: rgba(0, 0, 0, 0.55);
                    color: #fff;
                    border: 1px solid rgba(255,255,255,0.2);
                    width: 44px;
                    height: 44px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    cursor: pointer;
                    transition: all 0.2s ease;
                    z-index: 5;
                }
                .gallery-nav-btn:hover {
                    background: var(--tw-green);
                    border-color: var(--tw-green);
                    transform: translateY(-50%) scale(1.1);
                }
                .gallery-thumb-item.active {
                    border-color: var(--tw-beige) !important;
                    box-shadow: 0 0 10px rgba(230, 222, 178, 0.4);
                    transform: translateY(-2px);
                }
                .gallery-thumb-item:hover {
                    border-color: rgba(255,255,255,0.5);
                }
                .tw-lightbox-modal {
                    display: none;
                    position: fixed;
                    z-index: 99999;
                    inset: 0;
                    background: rgba(0, 0, 0, 0.92);
                    backdrop-filter: blur(8px);
                    justify-content: center;
                    align-items: center;
                    padding: 1.5rem;
                }
                .tw-lightbox-modal img {
                    max-width: 95vw;
                    max-height: 92vh;
                    object-fit: contain;
                    border-radius: 8px;
                    box-shadow: 0 10px 40px rgba(0,0,0,0.8);
                }
                .tw-lightbox-close {
                    position: absolute;
                    top: 20px;
                    right: 25px;
                    color: #fff;
                    font-size: 38px;
                    font-weight: bold;
                    cursor: pointer;
                    z-index: 100000;
                    line-height: 1;
                    transition: color 0.2s;
                }
                .tw-lightbox-close:hover {
                    color: var(--tw-beige);
                }
            </style>

            <script>
                const galleryImages = [
                    @foreach ($post->images as $img)
                        "{{ $img->url() }}",
                    @endforeach
                ];
                let currentGalleryIndex = 0;

                function setGalleryImage(index) {
                    if (index < 0 || index >= galleryImages.length) return;
                    currentGalleryIndex = index;
                    
                    const mainImg = document.getElementById('gallery_main_img');
                    const idxText = document.getElementById('gallery_current_idx');
                    
                    if (mainImg) {
                        mainImg.style.opacity = '0.3';
                        setTimeout(() => {
                            mainImg.src = galleryImages[index];
                            mainImg.style.opacity = '1';
                        }, 120);
                    }
                    if (idxText) idxText.textContent = index + 1;

                    document.querySelectorAll('.gallery-thumb-item').forEach((thumb, i) => {
                        thumb.classList.toggle('active', i === index);
                    });
                }

                function changeGalleryImage(direction) {
                    let newIndex = currentGalleryIndex + direction;
                    if (newIndex < 0) newIndex = galleryImages.length - 1;
                    if (newIndex >= galleryImages.length) newIndex = 0;
                    setGalleryImage(newIndex);
                }

                function openLightbox() {
                    const modal = document.getElementById('infographic_lightbox');
                    const modalImg = document.getElementById('lightbox_img');
                    if (modal && modalImg) {
                        modalImg.src = galleryImages[currentGalleryIndex];
                        modal.style.display = 'flex';
                        document.body.style.overflow = 'hidden';
                    }
                }

                function closeLightbox(event) {
                    if (event.target.id === 'infographic_lightbox' || event.target.classList.contains('tw-lightbox-close')) {
                        const modal = document.getElementById('infographic_lightbox');
                        if (modal) {
                            modal.style.display = 'none';
                            document.body.style.overflow = '';
                        }
                    }
                }

                document.addEventListener('keydown', (e) => {
                    const modal = document.getElementById('infographic_lightbox');
                    if (modal && modal.style.display === 'flex') {
                        if (e.key === 'Escape') closeLightbox({ target: { id: 'infographic_lightbox' } });
                        if (e.key === 'ArrowRight') changeGalleryImage(-1);
                        if (e.key === 'ArrowLeft') changeGalleryImage(1);
                    }
                });
            </script>
        @elseif ($post->cover_image)
            <div class="tw-card" style="margin-bottom: 2rem;">
                <div class="tw-card-media" style="aspect-ratio: 16/9;">
                    <img src="{{ asset('storage/'.$post->cover_image) }}" alt="{{ $post->title }}">
                </div>
            </div>
        @endif

        @if ($post->supportsVideo() && $post->videoEmbedSrc())
            <div class="tw-video" style="margin-bottom: 2rem;">
                <iframe src="{{ $post->videoEmbedSrc() }}" title="فيديو" allowfullscreen loading="lazy"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"></iframe>
            </div>
        @endif

        @if ($post->excerpt)
            <p style="font-size: 1.2rem; color: var(--tw-muted); margin-bottom: 2rem; font-weight: 600; line-height: 1.6;">{{ $post->excerpt }}</p>
        @endif

        <!-- Main Body Text -->
        @if (!empty($post->body))
            <div class="tw-prose" style="margin-bottom: 2rem;">
                {!! \App\Models\Post::linkify($post->body) !!}
            </div>
        @endif

        <!-- Investigation Persons Display Section -->
        @if ($post->type === \App\Models\Post::TYPE_INVESTIGATION && !empty($post->body))
            <div class="tw-prose" style="margin-bottom: 2.5rem; font-size: 1.1rem; line-height: 1.85;">
                {!! \App\Models\Post::linkify($post->body) !!}
            </div>
        @endif

        @if ($post->type === \App\Models\Post::TYPE_INVESTIGATION && is_array($post->investigation_persons) && count($post->investigation_persons) > 0)
            <div class="tw-investigation-persons" style="margin: 3rem 0;">
                <div class="tw-section-title" style="margin-bottom: 1.75rem;">
                    <h2>شخصيات التحقيق</h2>
                </div>
                <div style="display: flex; flex-direction: column; gap: 2rem;">
                    @foreach ($post->investigation_persons as $p)
                        <div class="tw-person-investigation-card" style="background: #ffffff; border: 1px solid var(--tw-line); border-radius: var(--tw-radius); overflow: hidden; box-shadow: var(--tw-shadow-sm);">
                            <!-- Upper Person Header Section -->
                            <div style="padding: 1.5rem; background: linear-gradient(135deg, rgba(45, 106, 79, 0.06), #ffffff); border-bottom: 1px solid var(--tw-line); display: flex; gap: 1.5rem; align-items: center; flex-wrap: wrap;">
                                @if(!empty($p['image']))
                                    <img src="{{ asset('storage/' . $p['image']) }}" alt="{{ $p['name'] ?? '' }}" style="width: 130px; height: 130px; border-radius: var(--tw-radius-sm); object-fit: cover; border: 3px solid var(--tw-green); box-shadow: var(--tw-shadow-sm); flex-shrink: 0;">
                                @endif
                                <div style="flex: 1; min-width: 220px;">
                                    @if(!empty($p['name']))
                                        <h3 style="margin: 0; font-size: 1.35rem; font-weight: 800; color: var(--tw-green-dark);">{{ $p['name'] }}</h3>
                                    @endif
                                    @if(!empty($p['title']))
                                        <div style="margin-top: 0.35rem;">
                                            <span class="tw-pill" style="font-size: 0.82rem; background: var(--tw-green); color: #fff;">{{ $p['title'] }}</span>
                                        </div>
                                    @endif
                                    @if(!empty($p['bio']))
                                        <p class="tw-muted" style="margin: 0.6rem 0 0; font-size: 0.98rem; font-weight: 600; line-height: 1.5;">{{ $p['bio'] }}</p>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Lower Investigation Body Section -->
                            <div style="padding: 1.5rem; background: #ffffff;">
                                @if(!empty($p['body']))
                                    <div class="tw-prose" style="font-size: 1.1rem; line-height: 1.85; color: var(--tw-text);">
                                        {!! \App\Models\Post::linkify($p['body']) !!}
                                    </div>
                                @endif
                                @if(!empty($p['external_url']))
                                    <div style="margin-top: 1.25rem; padding-top: 1rem; border-top: 1px dashed var(--tw-line);">
                                        <a href="{{ $p['external_url'] }}" target="_blank" rel="noopener" class="tw-btn tw-btn-ghost tw-btn-sm">
                                            🔗 رابط خارجي مرتبط بالشخصية ↗
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- External URL Display (For Reports, Articles, Infographics) -->
        @if ($post->external_url)
            <div style="margin: 2rem 0; padding: 1.25rem; background: var(--tw-green-pale); border: 1px solid var(--tw-green); border-radius: var(--tw-radius-sm); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <span style="font-weight: 800; color: var(--tw-green-dark); font-size: 1rem;">رابط خارجي مرتبط بالمنشور</span>
                    <p class="tw-help" style="margin: 0.15rem 0 0; color: var(--tw-muted);">يمكنك الانتقال للمصدر الخارجي من هنا.</p>
                </div>
                <a href="{{ $post->external_url }}" target="_blank" rel="noopener" class="tw-btn tw-btn-primary tw-btn-sm">
                    زيارة الرابط الخارجي ↗
                </a>
            </div>
        @endif

        @if ($related->isNotEmpty())
            <div class="tw-section-title" style="margin-top: 3.5rem;">
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
