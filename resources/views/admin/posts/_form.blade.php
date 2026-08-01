@php
    /** @var \App\Models\Post|null $post */
    $post = $post ?? null;
    $types = \App\Models\Post::types();
    $existingPersons = old('persons', $post->investigation_persons ?? []);
@endphp

<!-- Section Select -->
<div class="tw-field">
    <label for="type">القسم</label>
    <select class="tw-select" id="type" name="type" required onchange="toggleSectionFields()">
        @foreach ($types as $value => $label)
            <option value="{{ $value }}" @selected(old('type', $post->type ?? 'news') === $value)>{{ $label }}</option>
        @endforeach
    </select>
</div>

<!-- Title Field (Always Visible) -->
<div class="tw-field" id="field_title">
    <label for="title" id="label_title">العنوان</label>
    <input class="tw-input" type="text" id="title" name="title" required maxlength="255"
        value="{{ old('title', $post->title ?? '') }}">
</div>

<!-- Author Name (For Reports) -->
<div class="tw-field" id="field_author_name" style="display: none;">
    <label for="author_name">اسم الشخص أو الكاتب</label>
    <input class="tw-input" type="text" id="author_name" name="author_name" maxlength="255"
        value="{{ old('author_name', $post->author_name ?? '') }}" placeholder="أدخل اسم الشخص أو الكاتب...">
</div>

<!-- Excerpt Field (For News, Articles, Reels, Dialogues, Podcast) -->
<div class="tw-field" id="field_excerpt" style="display: none;">
    <label for="excerpt" id="label_excerpt">مقدمة / ملخص (اختياري)</label>
    <textarea class="tw-textarea" id="excerpt" name="excerpt" rows="3" style="min-height: 90px;">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
</div>

<!-- Body Field (For News, Reports, Articles, Infographics, Video Types) -->
<div class="tw-field" id="field_body" style="display: none;">
    <label for="body" id="label_body">النص الكامل</label>
    <textarea class="tw-textarea" id="body" name="body" rows="8">{{ old('body', $post->body ?? '') }}</textarea>
</div>

<!-- External URL Field (For Reports, Articles, Infographics) -->
<div class="tw-field" id="field_external_url" style="display: none;">
    <label for="external_url">رابط خارجي (اختياري)</label>
    <input class="tw-input" type="url" id="external_url" name="external_url" placeholder="https://..."
        value="{{ old('external_url', $post->external_url ?? '') }}">
</div>

<!-- Video URL Field (Only for Reels, Dialogues, Podcast) -->
<div class="tw-field" id="field_video_url" style="display: none;">
    <label for="video_url">رابط فيديو يوتيوب أو Shorts أو بودكاست</label>
    <input class="tw-input" type="url" id="video_url" name="video_url"
        value="{{ old('video_url', $post->video_url ?? '') }}" placeholder="https://www.youtube.com/shorts/... أو https://youtu.be/...">
</div>

<!-- Cover Image Field -->
<div class="tw-field" id="field_cover_image" style="display: none;">
    <label for="cover_image">صورة الغلاف الرئيسية</label>
    <input class="tw-input" type="file" id="cover_image" name="cover_image" accept="image/*">
    <p class="tw-help">صورة JPG أو PNG أو WebP — حتى 5 ميجابايت.</p>
    @if ($post?->thumbnailUrl())
        <p class="tw-help" style="margin-top: 0.5rem;">
            الحالية:
            <a href="{{ $post->thumbnailUrl() }}" target="_blank" rel="noopener">عرض</a>
        </p>
    @endif
</div>

<!-- Multiple Images Field (For Infographics & Reports) -->
<div class="tw-field" id="field_multiple_images" style="display: none; background: #fafafa; border: 1px dashed var(--tw-line); border-radius: var(--tw-radius-sm); padding: 1.25rem; margin-top: 1rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.85rem; flex-wrap: wrap; gap: 0.5rem;">
        <div>
            <h3 style="margin: 0; font-size: 1.05rem; color: var(--tw-green-dark); font-weight: 700;">إضافة صور داخل المنشور (صور متعددة)</h3>
            <p class="tw-help" style="margin-top: 0.15rem;">اختر أكثر من صورة دفعة واحدة وترتيبها بسهولة.</p>
        </div>
        <button type="button" class="tw-btn tw-btn-primary tw-btn-sm" onclick="document.getElementById('infographic_images_input').click()">
            اختيار صور
        </button>
        <input type="file" name="images[]" id="infographic_images_input" multiple accept="image/*" style="display: none;" onchange="handleNewImagesSelect(this.files)">
    </div>

    <!-- Existing Images List -->
    @if ($post && $post->images->isNotEmpty())
        <div style="margin-top: 1rem;">
            <h4 style="font-size: 0.88rem; font-weight: 700; color: var(--tw-text); margin-bottom: 0.5rem;">الصور الحالية المرفوعة:</h4>
            <div id="existing_images_grid" class="tw-image-sort-grid">
                @foreach ($post->images as $index => $img)
                    <div class="tw-sort-item" draggable="true" data-id="{{ $img->id }}" style="position: relative; border: 1px solid var(--tw-line); border-radius: var(--tw-radius-sm); overflow: hidden; background: #fff;">
                        <img src="{{ $img->url() }}" alt="" style="width: 100%; height: 110px; object-fit: cover; display: block;">
                        <input type="hidden" class="image-order-input" name="image_order[{{ $img->id }}]" value="{{ $img->sort_order }}">
                        <div style="padding: 0.3rem; display: flex; justify-content: space-between; align-items: center; background: #fafafa;">
                            <label style="display: flex; align-items: center; gap: 4px; color: var(--tw-danger); font-size: 0.75rem; cursor: pointer; margin: 0;">
                                <input type="checkbox" name="delete_images[]" value="{{ $img->id }}" onchange="toggleDeleteStyle(this)">
                                حذف
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<!-- Investigation Persons Form (Only For Investigation Type) -->
<div class="tw-field" id="field_investigation_persons" style="display: none; background: rgba(45, 106, 79, 0.04); border: 2px solid var(--tw-green); border-radius: var(--tw-radius); padding: 1.5rem; margin-top: 1.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem;">
        <div>
            <h3 style="margin: 0; font-size: 1.15rem; color: var(--tw-green-dark); font-weight: 800;">شخصيات التحقيق</h3>
            <p class="tw-help" style="margin-top: 0.2rem;">أضف الشخصيات المستهدفة داخل التحقيق (اسم، صورة، نص، رابط خارجي).</p>
        </div>
        <button type="button" class="tw-btn tw-btn-primary tw-btn-sm" onclick="addPersonForm()">
            + إضافة شخصية جديدة
        </button>
    </div>

    <div id="persons_container" style="display: flex; flex-direction: column; gap: 1.25rem;">
        <!-- Dynamic Persons Forms injected here -->
    </div>
</div>

<div class="tw-field" style="margin-top: 1.5rem;">
    <label class="tw-check">
        <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $post->is_published ?? false))>
        نشر على الموقع فور الحفظ
    </label>
</div>

@if ($post)
    <div class="tw-field">
        <label class="tw-check">
            <input type="checkbox" name="regenerate_slug" value="1" @checked(old('regenerate_slug'))>
            إعادة توليد الرابط (slug) من العنوان
        </label>
        <p class="tw-help">الرابط الحالي: <code style="color: var(--tw-green);">{{ $post->slug }}</code></p>
    </div>
@endif

<style>
.tw-person-card {
    background: #ffffff;
    border: 1px solid var(--tw-line);
    border-radius: var(--tw-radius-sm);
    padding: 1.25rem;
    position: relative;
    box-shadow: var(--tw-shadow-sm);
}
.tw-person-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid var(--tw-line);
}
.tw-image-sort-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
    gap: 0.85rem;
}
.tw-sort-item.mark-delete {
    opacity: 0.35;
    filter: grayscale(1);
    border-color: var(--tw-danger) !important;
}
</style>

<script>
const initialPersons = @json($existingPersons);

document.addEventListener('DOMContentLoaded', () => {
    toggleSectionFields();
    renderInitialPersons();
});

function toggleSectionFields() {
    const type = document.getElementById('type').value;

    // Elements
    const fieldTitle = document.getElementById('field_title');
    const labelTitle = document.getElementById('label_title');
    const fieldAuthorName = document.getElementById('field_author_name');
    const fieldExcerpt = document.getElementById('field_excerpt');
    const labelExcerpt = document.getElementById('label_excerpt');
    const fieldBody = document.getElementById('field_body');
    const labelBody = document.getElementById('label_body');
    const fieldExternalUrl = document.getElementById('field_external_url');
    const fieldVideoUrl = document.getElementById('field_video_url');
    const fieldCoverImage = document.getElementById('field_cover_image');
    const fieldMultipleImages = document.getElementById('field_multiple_images');
    const fieldInvestigationPersons = document.getElementById('field_investigation_persons');

    // Default Hide All Custom Fields
    fieldAuthorName.style.display = 'none';
    fieldExcerpt.style.display = 'none';
    fieldBody.style.display = 'none';
    fieldExternalUrl.style.display = 'none';
    fieldVideoUrl.style.display = 'none';
    fieldCoverImage.style.display = 'none';
    fieldMultipleImages.style.display = 'none';
    fieldInvestigationPersons.style.display = 'none';

    // 1. NEWS (أخبار)
    if (type === 'news') {
        labelTitle.textContent = 'العنوان الرئيسي';
        fieldTitle.style.display = 'block';
        labelExcerpt.textContent = 'مقدمة المنشور (اختياري)';
        fieldExcerpt.style.display = 'block';
        labelBody.textContent = 'النص الكامل';
        fieldBody.style.display = 'block';
        fieldCoverImage.style.display = 'block';
    }
    // 2. REPORTS (تقارير)
    else if (type === 'reports') {
        labelTitle.textContent = 'عنوان التقرير';
        fieldTitle.style.display = 'block';
        fieldAuthorName.style.display = 'block';
        labelBody.textContent = 'نص التقرير الكامل';
        fieldBody.style.display = 'block';
        fieldExternalUrl.style.display = 'block';
        fieldCoverImage.style.display = 'block';
        fieldMultipleImages.style.display = 'block';
    }
    // 3. INVESTIGATION (تحقيقات)
    else if (type === 'investigation') {
        labelTitle.textContent = 'عنوان التحقيق';
        fieldTitle.style.display = 'block';
        labelBody.textContent = 'النص الكامل للتحقيق';
        fieldBody.style.display = 'block';
        fieldCoverImage.style.display = 'block';
        fieldInvestigationPersons.style.display = 'block';
    }
    // 4. ARTICLE (مقالات)
    else if (type === 'article') {
        labelTitle.textContent = 'عنوان المقال';
        fieldTitle.style.display = 'block';
        labelExcerpt.textContent = 'مقدمة (اختيارية)';
        fieldExcerpt.style.display = 'block';
        labelBody.textContent = 'النص الكامل للمقال';
        fieldBody.style.display = 'block';
        fieldCoverImage.style.display = 'block';
        fieldExternalUrl.style.display = 'block';
    }
    // 5. INFOGRAPHICS (إنفوجرافيك)
    else if (type === 'infographics') {
        labelTitle.textContent = 'عنوان الإنفوجرافيك';
        fieldTitle.style.display = 'block';
        labelBody.textContent = 'النص التقليدي';
        fieldBody.style.display = 'block';
        fieldCoverImage.style.display = 'block';
        fieldExternalUrl.style.display = 'block';
        fieldMultipleImages.style.display = 'block';
    }
    // 6. VIDEO TYPES (ريلز، حوارات، بودكاست)
    else if (['reels', 'dialogues', 'podcast'].includes(type)) {
        labelTitle.textContent = 'العنوان';
        fieldTitle.style.display = 'block';
        labelExcerpt.textContent = 'مقدمة';
        fieldExcerpt.style.display = 'block';
        labelBody.textContent = 'النص (اختياري)';
        fieldBody.style.display = 'block';
        fieldVideoUrl.style.display = 'block';
        fieldCoverImage.style.display = 'block';
    }
}

function renderInitialPersons() {
    const container = document.getElementById('persons_container');
    container.innerHTML = '';

    if (Array.isArray(initialPersons) && initialPersons.length > 0) {
        initialPersons.forEach(p => addPersonForm(p));
    } else {
        // Add one empty person form by default for investigation
        addPersonForm();
    }
}

function addPersonForm(data = {}) {
    const container = document.getElementById('persons_container');
    const index = container.children.length;

    const card = document.createElement('div');
    card.className = 'tw-person-card';
    card.dataset.index = index;

    const imgPath = data.image ? `{{ asset('storage') }}/${data.image}` : '';

    card.innerHTML = `
        <div class="tw-person-card-header">
            <h4 style="margin: 0; font-size: 0.95rem; font-weight: 700; color: var(--tw-green-dark);">شخصية #${index + 1}</h4>
            <button type="button" class="tw-btn-action-icon" style="color: var(--tw-danger); border-color: var(--tw-danger);" onclick="removePersonForm(this)" title="حذف الشخصية">🗑️ حذف الشخصية</button>
        </div>
        <input type="hidden" name="persons[${index}][existing_image]" value="${data.image || ''}">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.85rem; margin-bottom: 0.85rem;">
            <div>
                <label style="font-size: 0.8rem; font-weight: 700;">اسم الشخصية / عنوان جانبي:</label>
                <input class="tw-input" type="text" name="persons[${index}][name]" value="${data.name || ''}" placeholder="اسم الشخصية..." style="margin-top: 0.2rem;">
            </div>
            <div>
                <label style="font-size: 0.8rem; font-weight: 700;">المسمى الوظيفي / الصفة:</label>
                <input class="tw-input" type="text" name="persons[${index}][title]" value="${data.title || ''}" placeholder="مثال: استقصائي - باحث" style="margin-top: 0.2rem;">
            </div>
            <div>
                <label style="font-size: 0.8rem; font-weight: 700;">صورة كبيرة للشخصية:</label>
                <input class="tw-input" type="file" name="person_image_${index}" accept="image/*" style="padding: 0.3rem; margin-top: 0.2rem;">
                ${imgPath ? `<p class="tw-help" style="margin-top: 0.2rem;">الحالية: <a href="${imgPath}" target="_blank">معاينة</a></p>` : ''}
            </div>
        </div>
        <div style="margin-bottom: 0.85rem;">
            <label style="font-size: 0.8rem; font-weight: 700;">نبذة قصيرة عن الشخصية:</label>
            <input class="tw-input" type="text" name="persons[${index}][bio]" value="${data.bio || ''}" placeholder="نبذة مختصرة عن الشخصية وخلفيتها..." style="margin-top: 0.2rem;">
        </div>
        <div style="margin-bottom: 0.85rem;">
            <label style="font-size: 0.8rem; font-weight: 700;">نص التحقيق الخاص بها:</label>
            <textarea class="tw-textarea" name="persons[${index}][body]" rows="5" placeholder="اكتب النص الاستقصائي والتحقيقي الخاص بهذه الشخصية..." style="margin-top: 0.2rem;">${data.body || ''}</textarea>
        </div>
        <div>
            <label style="font-size: 0.8rem; font-weight: 700;">رابط خارجي (اختياري):</label>
            <input class="tw-input" type="url" name="persons[${index}][external_url]" value="${data.external_url || ''}" placeholder="https://..." style="margin-top: 0.2rem;">
        </div>
    `;

    container.appendChild(card);
}

function removePersonForm(btn) {
    const card = btn.closest('.tw-person-card');
    card.remove();
    reindexPersons();
}

function reindexPersons() {
    const container = document.getElementById('persons_container');
    Array.from(container.children).forEach((card, index) => {
        card.dataset.index = index;
        card.querySelector('h4').textContent = `شخصية #${index + 1}`;
        card.querySelectorAll('[name^="persons["]').forEach(input => {
            const name = input.getAttribute('name');
            input.setAttribute('name', name.replace(/persons\[\d+\]/, `persons[${index}]`));
        });
        card.querySelectorAll('[name^="person_image_"]').forEach(fileInput => {
            fileInput.setAttribute('name', `person_image_${index}`);
        });
    });
}

function toggleDeleteStyle(checkbox) {
    const item = checkbox.closest('.tw-sort-item');
    if (item) {
        item.classList.toggle('mark-delete', checkbox.checked);
    }
}
</script>
