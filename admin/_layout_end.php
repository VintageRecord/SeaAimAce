            </div><!-- /.edit-panel -->

            <div class="preview-panel" id="preview-panel">
                <div class="preview-toolbar">
                    <span>Preview</span>
                    <input class="preview-url" id="preview-url" value="../index.php" readonly>
                    <button class="preview-refresh-btn" onclick="refreshPreview()">Refresh</button>
                </div>
                <div class="preview-loading" id="preview-loading"></div>
                <iframe id="site-preview" src="../index.php" title="Site Preview"></iframe>
            </div>
        </div><!-- /.split-layout -->
    </div><!-- /.main-content -->
</div><!-- /.admin-layout -->

<script>
// ── Preview toggle ──
const splitEl   = document.getElementById('split-layout');
const toggleBtn = document.getElementById('preview-toggle-btn');
const previewEl = document.getElementById('site-preview');
const loadingEl = document.getElementById('preview-loading');
let previewOpen = false;

function togglePreview() {
    previewOpen = !previewOpen;
    splitEl.classList.toggle('with-preview', previewOpen);
    if (toggleBtn) {
        toggleBtn.textContent = previewOpen ? 'Hide Preview' : 'Show Preview';
        toggleBtn.classList.toggle('active', previewOpen);
    }
    if (previewOpen && previewEl) refreshPreview();
}

function refreshPreview() {
    if (!previewEl) return;
    loadingEl && loadingEl.classList.add('active');
    loadingEl && loadingEl.classList.remove('done');
    previewEl.onload = () => {
        loadingEl && loadingEl.classList.remove('active');
        loadingEl && loadingEl.classList.add('done');
        setTimeout(() => loadingEl && loadingEl.classList.remove('done'), 400);
    };
    previewEl.src = '../index.php?_=' + Date.now();
}

// ── AJAX save for all forms with data-ajax ──
document.querySelectorAll('form[data-ajax]').forEach(form => {
    const statusEl = form.querySelector('.save-status');

    form.addEventListener('submit', async e => {
        e.preventDefault();
        const btn = form.querySelector('[type=submit]');
        if (btn) { btn.disabled = true; btn.textContent = 'Saving...'; }

        try {
            const fd = new FormData(form);
            // Handle unchecked checkboxes that use indexed names
            form.querySelectorAll('input[type=checkbox][data-name]').forEach(cb => {
                if (!cb.checked) fd.delete(cb.name);
            });

            const res = await fetch(window.location.pathname, {
                method: 'POST',
                body: fd,
                headers: { 'X-Ajax': '1' }
            });
            const json = await res.json();

            if (json.success) {
                if (statusEl) {
                    statusEl.textContent = 'Saved';
                    statusEl.classList.remove('error');
                    statusEl.classList.add('visible');
                    setTimeout(() => statusEl.classList.remove('visible'), 2500);
                }
                if (previewOpen) refreshPreview();
            } else {
                if (statusEl) {
                    statusEl.textContent = json.error || 'Error saving';
                    statusEl.classList.add('visible', 'error');
                }
            }
        } catch (err) {
            if (statusEl) {
                statusEl.textContent = 'Network error';
                statusEl.classList.add('visible', 'error');
            }
        } finally {
            if (btn) {
                btn.disabled = false;
                btn.textContent = 'Save';
            }
        }
    });
});

// ── Live preview: debounce input changes and refresh preview ──
let debounceTimer;
document.addEventListener('input', e => {
    if (!previewOpen) return;
    if (e.target.closest('form[data-live]')) {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            const form = e.target.closest('form[data-live]');
            if (form) form.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
        }, 800);
    }
});

function removeBlock(btn) {
    btn.closest('.item-block').remove();
}
</script>
</body>
</html>
