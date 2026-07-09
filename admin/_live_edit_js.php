<?php
// $cms_page_key - e.g. 'live-edit-about.php' (for page switcher reset on cancel)
?>
<script>
const toolbar    = document.getElementById('cms-toolbar');
const linkPopup  = document.getElementById('cms-link-popup');
const indicator  = document.getElementById('save-indicator');
const hint       = document.getElementById('cms-hint');

let savedSelection = null;
let activeEl       = null;
let pendingSaves   = {};
let hideToolbarTimer;

document.querySelectorAll('[data-editable]').forEach(el => {
    el.contentEditable = 'true';
    el.spellcheck = true;
    el._originalContent = el.innerHTML;
    el._isDirty = false;

    el.addEventListener('focus', () => { activeEl = el; el.dataset.active = '1'; clearTimeout(hideToolbarTimer); });
    el.addEventListener('input', () => { el._isDirty = (el.innerHTML !== el._originalContent); });
    el.addEventListener('blur', evt => {
        const toEl = evt.relatedTarget;
        if (toolbar.contains(toEl) || linkPopup.contains(toEl)) return;
        el.removeAttribute('data-active');
        if (activeEl === el) activeEl = null;
        scheduleHideToolbar();
        if (el._isDirty) autoSave(el);
    });
    el.addEventListener('keydown', evt => {
        if (evt.key === 'Enter' && !evt.shiftKey) {
            if (['SPAN','H1','H2','H3','H4','BUTTON','A'].includes(el.tagName)) { evt.preventDefault(); el.blur(); }
        }
        if ((evt.ctrlKey || evt.metaKey) && evt.key === 's') { evt.preventDefault(); saveAll(); }
    });
    el.addEventListener('click', evt => { if (el.tagName === 'A' || el.closest('a')) evt.preventDefault(); });
});

document.addEventListener('selectionchange', () => {
    const sel = window.getSelection();
    if (!sel || sel.isCollapsed) { scheduleHideToolbar(); return; }
    const range = sel.getRangeAt(0);
    const container = range.commonAncestorContainer;
    const inEditable = container.nodeType === 3
        ? container.parentElement.closest('[data-editable]')
        : container.closest?.('[data-editable]');
    if (!inEditable) { scheduleHideToolbar(); return; }
    clearTimeout(hideToolbarTimer);
    savedSelection = range.cloneRange();
    positionToolbar(range);
    updateToolbarState();
    toolbar.classList.add('visible');
    hint.style.opacity = '0';
});

function scheduleHideToolbar() {
    hideToolbarTimer = setTimeout(() => { toolbar.classList.remove('visible'); closeLinkPopup(); }, 200);
}
function positionToolbar(range) {
    const rect = range.getBoundingClientRect();
    const tbW = toolbar.offsetWidth || 520, tbH = toolbar.offsetHeight || 44;
    let top  = rect.top  - tbH - 10 + window.scrollY;
    let left = rect.left + (rect.width / 2) - (tbW / 2) + window.scrollX;
    if (top < 56) top = rect.bottom + 10 + window.scrollY;
    if (left < 8) left = 8;
    if (left + tbW > window.innerWidth - 8) left = window.innerWidth - tbW - 8;
    toolbar.style.top = top + 'px'; toolbar.style.left = left + 'px';
}
function updateToolbarState() {
    document.getElementById('tb-bold')     .classList.toggle('active', document.queryCommandState('bold'));
    document.getElementById('tb-italic')   .classList.toggle('active', document.queryCommandState('italic'));
    document.getElementById('tb-underline').classList.toggle('active', document.queryCommandState('underline'));
    document.getElementById('tb-strike')   .classList.toggle('active', document.queryCommandState('strikeThrough'));
}
function e(evt) { evt.preventDefault(); restoreSelection(); }
function execCmd(cmd, val) {
    restoreSelection();
    document.execCommand(cmd, false, val || null);
    updateToolbarState();
    const sel = window.getSelection();
    if (sel && !sel.isCollapsed) {
        const container = sel.getRangeAt(0).commonAncestorContainer;
        const el = container.nodeType === 3 ? container.parentElement.closest('[data-editable]') : container.closest?.('[data-editable]');
        if (el) { el._isDirty = true; markPending(el); }
    }
}
function restoreSelection() {
    if (!savedSelection) return;
    const sel = window.getSelection();
    sel.removeAllRanges(); sel.addRange(savedSelection);
}
function applyFontSize(px) {
    if (!px) return;
    restoreSelection();
    const sel = window.getSelection();
    if (!sel || sel.isCollapsed) return;
    const range = sel.getRangeAt(0);
    const span = document.createElement('span');
    span.style.fontSize = px + 'px';
    try { range.surroundContents(span); } catch(err) { span.appendChild(range.extractContents()); range.insertNode(span); }
    const el = span.closest('[data-editable]');
    if (el) { el._isDirty = true; markPending(el); }
}
function applyColor(val) {
    restoreSelection(); document.execCommand('foreColor', false, val);
    const el = activeEl || document.querySelector('[data-editable][data-active]');
    if (el) { el._isDirty = true; markPending(el); }
}
function applyBgColor(val) {
    restoreSelection(); document.execCommand('hiliteColor', false, val);
    const el = activeEl || document.querySelector('[data-editable][data-active]');
    if (el) { el._isDirty = true; markPending(el); }
}
function toggleLinkPopup() {
    restoreSelection();
    if (linkPopup.classList.contains('visible')) { closeLinkPopup(); return; }
    const sel = window.getSelection();
    const url = sel?.anchorNode?.parentElement?.closest('a')?.href || '';
    document.getElementById('link-url-input').value = url;
    const tbRect = toolbar.getBoundingClientRect();
    linkPopup.style.top  = (tbRect.bottom + 8 + window.scrollY) + 'px';
    linkPopup.style.left = tbRect.left + 'px';
    linkPopup.classList.add('visible');
    document.getElementById('link-url-input').focus();
}
function closeLinkPopup() { linkPopup.classList.remove('visible'); }
function applyLink() {
    const url = document.getElementById('link-url-input').value.trim();
    restoreSelection();
    if (url) document.execCommand('createLink', false, url);
    else document.execCommand('unlink', false, null);
    closeLinkPopup();
}
function markPending(el) { pendingSaves[buildKey(el)] = el; }
function buildKey(el) {
    const t = el.dataset.type;
    if (t === 'setting') return `setting:${el.dataset.key}`;
    return `${t}:${el.dataset.id}:${el.dataset.field}`;
}
function getPayload(el) {
    const t = el.dataset.type, val = el.innerHTML;
    if (t === 'setting') return { type: 'setting', key: el.dataset.key, value: val };
    return { type: t, id: el.dataset.id, field: el.dataset.field, value: val };
}
async function autoSave(el) {
    markPending(el);
    const payload = getPayload(el);
    el.classList.add('saving');
    try {
        const res  = await fetch('live-edit-save.php', { method:'POST', headers:{'Content-Type':'application/json','X-Ajax':'1'}, body:JSON.stringify(payload) });
        const json = await res.json();
        el.classList.remove('saving');
        if (json.success) {
            el.classList.add('saved');
            setTimeout(() => el.classList.remove('saved'), 1200);
            delete pendingSaves[buildKey(el)];
            el._originalContent = el.innerHTML; el._isDirty = false;
        } else { showIndicator(json.error || 'Save failed', true); }
    } catch(err) { el.classList.remove('saving'); showIndicator('Network error', true); }
}
async function saveAll() {
    const all = document.querySelectorAll('[data-editable]');
    const seen = new Set(), jobs = [];
    all.forEach(el => {
        const key = buildKey(el);
        if (seen.has(key)) return;
        seen.add(key);
        jobs.push(fetch('live-edit-save.php', { method:'POST', headers:{'Content-Type':'application/json','X-Ajax':'1'}, body:JSON.stringify(getPayload(el)) }));
    });
    showIndicator('Saving...');
    try { await Promise.all(jobs); pendingSaves = {}; showIndicator('All saved'); }
    catch(err) { showIndicator('Some saves failed', true); }
}
function showIndicator(msg, isError) {
    indicator.textContent = msg;
    indicator.classList.toggle('error', !!isError);
    indicator.classList.add('visible');
    if (!isError) setTimeout(() => indicator.classList.remove('visible'), 2500);
}
window.addEventListener('beforeunload', e => {
    if (Object.keys(pendingSaves).length > 0) { e.preventDefault(); e.returnValue = ''; }
});
document.addEventListener('click', () => { hint.style.opacity = '0'; }, { once: true });

// ── Image change buttons ──────────────────────────────────────
const imgPickerEl = document.getElementById('cms-img-picker');
let activeImg = null;
let activeImgType = 'src';

function injectImgBtn(el, type) {
    // For bg-key divs use the element itself; for img-key use a gallery wrapper if present
    const anchor = type === 'bg' ? el : (el.closest('.u-gallery-item') || el.closest('.u-back-slide') || el.parentElement || el);
    if (getComputedStyle(anchor).position === 'static') anchor.style.position = 'relative';

    const btn = document.createElement('button');
    btn.innerHTML = '&#128247; Change Image';
    btn.title = 'Change image';
    btn.style.cssText = 'position:absolute;top:8px;right:8px;z-index:9999;background:#E63946;color:#fff;border:none;border-radius:6px;padding:7px 12px;font-size:13px;font-weight:700;font-family:system-ui,sans-serif;cursor:pointer;line-height:1;letter-spacing:.03em;box-shadow:0 2px 8px rgba(0,0,0,.45);display:flex;align-items:center;gap:5px;white-space:nowrap;';
    btn.addEventListener('click', e => {
        e.preventDefault(); e.stopPropagation();
        activeImg = el; activeImgType = type;
        openImgPicker();
    });
    anchor.appendChild(btn);
}

document.querySelectorAll('[data-img-key]').forEach(el => injectImgBtn(el, 'src'));
document.querySelectorAll('[data-bg-key]').forEach(el => injectImgBtn(el, 'bg'));
function openImgPicker()  { if (imgPickerEl) imgPickerEl.style.display = 'flex'; }
function closeImgPicker() { if (imgPickerEl) imgPickerEl.style.display = 'none'; }
function switchImgTab(tab) {
    document.getElementById('img-pane-site').style.display    = tab === 'site'    ? 'grid' : 'none';
    document.getElementById('img-pane-uploads').style.display = tab === 'uploads' ? 'grid' : 'none';
    document.getElementById('img-tab-site').classList.toggle('active',    tab === 'site');
    document.getElementById('img-tab-uploads').classList.toggle('active', tab === 'uploads');
}
async function pickImg(src) {
    if (!activeImg) return;
    const displaySrc = '../' + src;
    closeImgPicker();
    // Update the DOM element
    if (activeImgType === 'bg') {
        // Preserve any existing gradient prefix (e.g. linear-gradient(...), url(...))
        const existing = activeImg.style.backgroundImage || '';
        const gradientMatch = existing.match(/^((?:linear-gradient|radial-gradient)\([^)]+\)\s*,\s*)/i);
        const prefix = gradientMatch ? gradientMatch[1] : '';
        activeImg.style.backgroundImage = prefix + "url('" + displaySrc + "')";
    } else {
        activeImg.src = displaySrc;
    }
    const key = activeImgType === 'bg' ? activeImg.dataset.bgKey : activeImg.dataset.imgKey;
    try {
        const res  = await fetch('live-edit-save.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Ajax': '1' },
            body: JSON.stringify({ type: 'image_src', key, value: displaySrc })
        });
        const json = await res.json();
        showIndicator(json.success ? 'Image updated' : (json.error || 'Save failed'), !json.success);
    } catch(e) { showIndicator('Network error', true); }
}
if (imgPickerEl) imgPickerEl.addEventListener('click', e => { if (e.target === imgPickerEl) closeImgPicker(); });

function switchPage(url) {
    if (!url) return;
    if (Object.keys(pendingSaves).length > 0) {
        if (!confirm('You have unsaved changes. Leave anyway?')) {
            document.getElementById('page-switcher').value = '<?= $cms_page_key ?? '' ?>';
            return;
        }
    }
    location.href = url;
}
const navPageMap = {
    './':           'live-edit.php',
    'about.php':    'live-edit-about.php',
    'gallery.php':  'live-edit-gallery.php',
    'team.php':     'live-edit-team.php',
    'faq.php':      'live-edit-faq.php',
    'contact.php':  'live-edit-contact.php',
};
document.querySelectorAll('.u-nav-link').forEach(link => {
    const raw = link.getAttribute('href') || '';
    const page = raw.replace(/^\.\.\//, '');
    if (navPageMap[page]) { link.href = navPageMap[page]; link.removeAttribute('target'); }
});
</script>
