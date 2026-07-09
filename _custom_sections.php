<?php
// Renders custom sections for $current_page.
// Include near the bottom of each public page, just before _footer.php
//
// $_cs_base:     optional prefix for asset/page URLs (e.g. '../' when included from admin/)
// $_cs_editable: when true (Live Editor pages), adds data-editable/data-bg-key hooks so
//                heading/body text and the image can be edited in place.

$_cs_base     ??= '';
$_cs_editable ??= false;

$_cs_page = $current_page ?? '';
if (!$_cs_page) return;

$_cs_rows = get_db()->prepare(
    'SELECT * FROM custom_sections WHERE page = ? AND enabled = 1 ORDER BY sort_order'
);
$_cs_rows->execute([$_cs_page]);
$_cs_list = $_cs_rows->fetchAll();

if (empty($_cs_list)) return;

$_cs_img_stmt = get_db()->prepare('SELECT * FROM custom_section_images WHERE section_id = ? ORDER BY sort_order');

// Preset display sizes for a section's gallery images. small/medium/large don't grow
// (flex-grow:0) so a couple of photos render at roughly their real size instead of always
// stretching to fill the row; "full" grows to fill so each image takes the whole row.
$_cs_sizes = [
    'small'  => ['flex' => '0 1 140px', 'max_h' => '160px'],
    'medium' => ['flex' => '0 1 240px', 'max_h' => '320px'],
    'large'  => ['flex' => '0 1 380px', 'max_h' => '460px'],
    'full'   => ['flex' => '1 1 100%',  'max_h' => '520px'],
];

$_cs_first = true;
foreach ($_cs_list as $_cs) {
    $bg   = $_cs['bg_color']   ?: '#f4f6f8';
    $fg   = $_cs['text_color'] ?: '#333333';
    $yt   = trim($_cs['youtube_url']);
    $id   = (int)$_cs['id'];
    $size = $_cs_sizes[$_cs['image_size']] ?? $_cs_sizes['medium'];

    $yt_id = '';
    if ($yt && preg_match('/(?:v=|\/embed\/|youtu\.be\/)([A-Za-z0-9_-]{11})/', $yt, $m)) {
        $yt_id = $m[1];
    }

    $_cs_img_stmt->execute([$id]);
    $_cs_images = $_cs_img_stmt->fetchAll();

    $headingAttrs = $_cs_editable ? ' data-editable data-type="custom_section" data-id="' . $id . '" data-field="heading"' : '';
    $bodyAttrs    = $_cs_editable ? ' data-editable data-type="custom_section" data-id="' . $id . '" data-field="body"'    : '';
    $linkAttrs    = $_cs_editable ? ' data-editable data-type="custom_section" data-id="' . $id . '" data-field="link_text"' : '';

    $anchor = $_cs_first ? ' id="custom-sections"' : '';
    echo '<section' . $anchor . ' style="background:' . h($bg) . ';color:' . h($fg) . ';padding:64px 24px;font-family:\'Segoe UI\',system-ui,sans-serif;border-top:4px solid #c0303b" class="custom-section">';
    echo '<div style="max-width:1100px;margin:0 auto">';

    if ($_cs['heading'] || $_cs_editable) {
        echo '<h2' . $headingAttrs . ' style="font-size:2rem;font-weight:700;margin:0 0 20px;line-height:1.2;color:' . h($fg) . '">' . sh($_cs['heading']) . '</h2>';
    }

    if (!empty($_cs_images) || $yt_id || $_cs_editable) {
        $mb = $_cs['body'] ? '28px' : '0';
        echo '<div style="display:flex;flex-wrap:wrap;gap:20px;align-items:flex-start;margin-bottom:' . $mb . '">';

        foreach ($_cs_images as $_i => $_ci) {
            $_ci_url = $_cs_base . (strpos($_ci['image'], '/') === false ? 'uploads/' . $_ci['image'] : $_ci['image']);
            $imgAttrs = $_cs_editable ? ' data-bg-key="custom_section_' . $id . '_img' . $_i . '" data-img-id="' . (int)$_ci['id'] . '"' : '';
            echo '<div' . $imgAttrs . ' style="flex:' . $size['flex'] . ';min-width:0;position:relative">';
            echo '<img src="' . h($_ci_url) . '" alt="" style="width:100%;border-radius:10px;display:block;object-fit:cover;max-height:' . $size['max_h'] . '">';
            echo '</div>';
        }

        // One extra empty slot in the Live Editor to add another image inline.
        if ($_cs_editable) {
            $_nextIdx = count($_cs_images);
            echo '<div data-bg-key="custom_section_' . $id . '_img' . $_nextIdx . '" data-new-slot="1" style="flex:' . $size['flex'] . ';min-width:0;min-height:120px;position:relative;border:2px dashed rgba(128,128,128,.4);border-radius:10px;display:flex;align-items:center;justify-content:center;color:inherit;opacity:.6;font-size:.85rem">+ Add image</div>';
        }

        if ($yt_id) {
            echo '<div style="flex:1 1 300px;min-width:0;aspect-ratio:16/9;border-radius:10px;overflow:hidden">';
            echo '<iframe src="https://www.youtube.com/embed/' . h($yt_id) . '" title="Video" frameborder="0"';
            echo ' allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"';
            echo ' allowfullscreen style="width:100%;height:100%;display:block"></iframe>';
            echo '</div>';
        }

        echo '</div>';
    }

    if ($_cs['body'] || $_cs_editable) {
        echo '<div' . $bodyAttrs . ' style="font-size:1rem;line-height:1.7;color:' . h($fg) . '">' . sh($_cs['body']) . '</div>';
    }

    if ($_cs['link_url']) {
        echo '<a href="' . h($_cs['link_url']) . '"' . $linkAttrs . ' style="display:inline-block;margin-top:24px;padding:12px 28px;border-radius:50px;background:' . h($fg) . ';color:' . h($bg) . ';text-decoration:none;font-weight:700;font-size:.9rem">' . sh($_cs['link_text'] ?: 'Learn more') . '</a>';
    }

    echo '</div></section>';
    $_cs_first = false;
}
