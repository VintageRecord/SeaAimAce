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
// stack_w is the equivalent width used when media_layout is "column" (stacked), since
// flex-basis controls height rather than width once the container's direction is column.
$_cs_sizes = [
    'small'  => ['flex' => '0 1 140px', 'max_h' => '160px', 'stack_w' => '200px'],
    'medium' => ['flex' => '0 1 240px', 'max_h' => '320px', 'stack_w' => '360px'],
    'large'  => ['flex' => '0 1 380px', 'max_h' => '460px', 'stack_w' => '520px'],
    'full'   => ['flex' => '1 1 100%',  'max_h' => '520px', 'stack_w' => '100%'],
];

// Same idea for video, sized independently of the image preset. "medium" matches the
// original hardcoded video size (flex:1 1 300px) so existing sections don't change.
$_cs_video_sizes = [
    'small'  => ['flex' => '0 1 220px', 'stack_w' => '300px'],
    'medium' => ['flex' => '1 1 300px', 'stack_w' => '480px'],
    'large'  => ['flex' => '0 1 480px', 'stack_w' => '640px'],
    'full'   => ['flex' => '1 1 100%',  'stack_w' => '100%'],
];

$_cs_first = true;
foreach ($_cs_list as $_cs) {
    $bg   = $_cs['bg_color']   ?: '#f4f6f8';
    $fg   = $_cs['text_color'] ?: '#333333';
    $id   = (int)$_cs['id'];
    $size = $_cs_sizes[$_cs['image_size']] ?? $_cs_sizes['medium'];
    $vsize = $_cs_video_sizes[$_cs['video_size']] ?? $_cs_video_sizes['medium'];

    $_cs_yt_urls = json_decode($_cs['youtube_urls'], true);
    if (!is_array($_cs_yt_urls)) $_cs_yt_urls = [];
    $_cs_yt_ids = [];
    foreach ($_cs_yt_urls as $_yt) {
        $_yt = trim($_yt);
        if ($_yt && preg_match('/(?:v=|\/embed\/|youtu\.be\/)([A-Za-z0-9_-]{11})/', $_yt, $m)) {
            $_cs_yt_ids[] = $m[1];
        }
    }

    $_cs_img_stmt->execute([$id]);
    $_cs_images = $_cs_img_stmt->fetchAll();

    $headingAttrs = $_cs_editable ? ' data-editable data-type="custom_section" data-id="' . $id . '" data-field="heading"' : '';
    $bodyAttrs    = $_cs_editable ? ' data-editable data-type="custom_section" data-id="' . $id . '" data-field="body"'    : '';

    $_cs_links = json_decode($_cs['links'], true);
    if (!is_array($_cs_links)) $_cs_links = [];

    $anchor = $_cs_first ? ' id="custom-sections"' : '';
    echo '<section' . $anchor . ' style="background:' . h($bg) . ';color:' . h($fg) . ';padding:64px 24px;font-family:\'Segoe UI\',system-ui,sans-serif;border-top:4px solid #c0303b" class="custom-section">';
    echo '<div style="max-width:1100px;margin:0 auto">';

    if ($_cs['heading'] || $_cs_editable) {
        echo '<h2' . $headingAttrs . ' style="font-size:2rem;font-weight:700;margin:0 0 20px;line-height:1.2;color:' . h($fg) . '">' . sh($_cs['heading']) . '</h2>';
    }

    $_cs_stacked = $_cs['media_layout'] === 'column';
    $_cs_imgItemStyle   = $_cs_stacked ? 'width:' . $size['stack_w'] . ';max-width:100%' : 'flex:' . $size['flex'];
    $_cs_videoItemStyle = $_cs_stacked ? 'width:' . $vsize['stack_w'] . ';max-width:100%;aspect-ratio:16/9' : 'flex:' . $vsize['flex'] . ';aspect-ratio:16/9';

    $_cs_mediaHtml = '';
    if (!empty($_cs_images) || !empty($_cs_yt_ids) || $_cs_editable) {
        $_cs_mediaHtml .= '<div style="display:flex;' . ($_cs_stacked ? 'flex-direction:column' : 'flex-wrap:wrap') . ';gap:20px;align-items:flex-start">';

        $_cs_imagesHtml = '';
        foreach ($_cs_images as $_i => $_ci) {
            $_ci_url = $_cs_base . (strpos($_ci['image'], '/') === false ? 'uploads/' . $_ci['image'] : $_ci['image']);
            $imgAttrs = $_cs_editable ? ' data-bg-key="custom_section_' . $id . '_img' . $_i . '" data-img-id="' . (int)$_ci['id'] . '"' : '';
            $_cs_imagesHtml .= '<div' . $imgAttrs . ' style="' . $_cs_imgItemStyle . ';min-width:0;position:relative">';
            $_cs_imagesHtml .= '<img src="' . h($_ci_url) . '" alt="" style="width:100%;border-radius:10px;display:block;object-fit:cover;max-height:' . $size['max_h'] . '">';
            $_cs_imagesHtml .= '</div>';
        }
        // One extra empty slot in the Live Editor to add another image inline.
        if ($_cs_editable) {
            $_nextIdx = count($_cs_images);
            $_cs_imagesHtml .= '<div data-bg-key="custom_section_' . $id . '_img' . $_nextIdx . '" data-new-slot="1" style="' . $_cs_imgItemStyle . ';min-width:0;min-height:120px;position:relative;border:2px dashed rgba(128,128,128,.4);border-radius:10px;display:flex;align-items:center;justify-content:center;color:inherit;opacity:.6;font-size:.85rem">+ Add image</div>';
        }

        $_cs_videoHtml = '';
        foreach ($_cs_yt_ids as $_yt_id) {
            $_cs_videoHtml .= '<div style="' . $_cs_videoItemStyle . ';min-width:0;border-radius:10px;overflow:hidden">';
            $_cs_videoHtml .= '<iframe src="https://www.youtube.com/embed/' . h($_yt_id) . '" title="Video" frameborder="0"';
            $_cs_videoHtml .= ' allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"';
            $_cs_videoHtml .= ' allowfullscreen style="width:100%;height:100%;display:block"></iframe>';
            $_cs_videoHtml .= '</div>';
        }

        $_cs_mediaHtml .= ($_cs['media_order'] === 'video_first')
            ? $_cs_videoHtml . $_cs_imagesHtml
            : $_cs_imagesHtml . $_cs_videoHtml;
        $_cs_mediaHtml .= '</div>';
    }

    $_cs_bodyHtml = '';
    if ($_cs['body'] || $_cs_editable) {
        $_cs_bodyHtml = '<div' . $bodyAttrs . ' style="font-size:1rem;line-height:1.7;color:' . h($fg) . '">' . sh($_cs['body']) . '</div>';
    }

    // Whichever block renders first gets the gap as margin-bottom; if only one exists, no gap needed.
    $_cs_gap = ($_cs_mediaHtml && $_cs_bodyHtml) ? 'margin-bottom:28px' : '';
    if ($_cs['text_position'] === 'above') {
        if ($_cs_bodyHtml) echo '<div style="' . $_cs_gap . '">' . $_cs_bodyHtml . '</div>';
        echo $_cs_mediaHtml;
    } else {
        if ($_cs_mediaHtml) echo '<div style="' . $_cs_gap . '">' . $_cs_mediaHtml . '</div>';
        echo $_cs_bodyHtml;
    }

    if (!empty($_cs_links)) {
        echo '<div style="display:flex;flex-wrap:wrap;gap:12px;margin-top:24px">';
        foreach ($_cs_links as $_li => $_link) {
            $_linkUrl = trim($_link['url'] ?? '');
            if ($_linkUrl === '') continue;
            $_linkAttrs = $_cs_editable ? ' data-editable data-type="custom_section_link" data-id="' . $id . ':' . $_li . '" data-field="text"' : '';
            echo '<a href="' . h($_linkUrl) . '"' . $_linkAttrs . ' style="display:inline-block;padding:12px 28px;border-radius:50px;background:' . h($fg) . ';color:' . h($bg) . ';text-decoration:none;font-weight:700;font-size:.9rem">' . sh($_link['text'] ?: 'Learn more') . '</a>';
        }
        echo '</div>';
    }

    echo '</div></section>';
    $_cs_first = false;
}
