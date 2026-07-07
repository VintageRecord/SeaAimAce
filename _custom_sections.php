<?php
// Renders custom sections for $current_page.
// Include near the bottom of each public page, just before <?php require __DIR__ . '/_footer.php'; ?>

$_cs_page = $current_page ?? '';
if (!$_cs_page) return;

$_cs_rows = get_db()->prepare(
    'SELECT * FROM custom_sections WHERE page = ? AND enabled = 1 ORDER BY sort_order'
);
$_cs_rows->execute([$_cs_page]);
$_cs_list = $_cs_rows->fetchAll();

if (empty($_cs_list)) return;

foreach ($_cs_list as $_cs):
    $bg   = $_cs['bg_color']   ?: '#ffffff';
    $fg   = $_cs['text_color'] ?: '#333333';
    $yt   = trim($_cs['youtube_url']);
    $img  = trim($_cs['image']);

    // Extract YouTube video ID from various URL formats
    $yt_id = '';
    if ($yt) {
        if (preg_match('/(?:v=|\/embed\/|youtu\.be\/)([A-Za-z0-9_-]{11})/', $yt, $m)) {
            $yt_id = $m[1];
        }
    }

    // Build image URL
    $img_url = '';
    if ($img) {
        $img_url = (strpos($img, '/') === false) ? 'uploads/' . $img : $img;
    }
?>
<section style="background:<?= h($bg) ?>;color:<?= h($fg) ?>;padding:64px 24px;font-family:'Segoe UI',system-ui,sans-serif" class="custom-section">
  <div style="max-width:1100px;margin:0 auto">

    <?php if ($_cs['heading']): ?>
    <h2 style="font-size:2rem;font-weight:700;margin:0 0 20px;line-height:1.2;color:<?= h($fg) ?>"><?= sh($_cs['heading']) ?></h2>
    <?php endif; ?>

    <?php if ($img_url || $yt_id): ?>
    <div style="display:flex;flex-wrap:wrap;gap:32px;align-items:flex-start;margin-bottom:<?= $_cs['body'] ? '28px' : '0' ?>">

      <?php if ($img_url): ?>
      <div style="flex:1 1 300px;min-width:0">
        <img src="<?= h($img_url) ?>" alt="" style="width:100%;border-radius:10px;display:block;object-fit:cover;max-height:400px">
      </div>
      <?php endif; ?>

      <?php if ($yt_id): ?>
      <div style="flex:1 1 300px;min-width:0;aspect-ratio:16/9;border-radius:10px;overflow:hidden">
        <iframe
          src="https://www.youtube.com/embed/<?= h($yt_id) ?>"
          title="Video"
          frameborder="0"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
          allowfullscreen
          style="width:100%;height:100%;display:block">
        </iframe>
      </div>
      <?php endif; ?>

    </div>
    <?php endif; ?>

    <?php if ($_cs['body']): ?>
    <div style="font-size:1rem;line-height:1.7;color:<?= h($fg) ?>"><?= sh($_cs['body']) ?></div>
    <?php endif; ?>

  </div>
</section>
<?php endforeach; ?>
