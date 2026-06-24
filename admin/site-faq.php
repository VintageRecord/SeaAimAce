<?php
require_once dirname(__DIR__) . '/config.php';
require_admin_login();

$db = get_db();

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'save_settings') {
        foreach (['faq_meta_title','faq_hero_heading','faq_hero_sub','faq_section_heading','faq_cta_heading','faq_cta_text','faq_cta_btn'] as $f) {
            save_setting($f, $_POST[$f] ?? '');
        }
    } elseif ($action === 'save_faq') {
        $id  = (int)($_POST['id'] ?? 0);
        $q   = trim($_POST['question'] ?? '');
        $a   = trim($_POST['answer'] ?? '');
        $ord = (int)($_POST['sort_order'] ?? 0);
        if ($id) {
            $db->prepare('UPDATE faq_items SET question=?,answer=?,sort_order=? WHERE id=?')->execute([$q,$a,$ord,$id]);
        } else {
            $db->prepare('INSERT INTO faq_items (question,answer,sort_order) VALUES (?,?,?)')->execute([$q,$a,$ord]);
        }
    } elseif ($action === 'delete_faq') {
        $db->prepare('DELETE FROM faq_items WHERE id=?')->execute([(int)($_POST['id'] ?? 0)]);
    }

    if (!empty($_SERVER['HTTP_X_AJAX'])) { header('Content-Type: application/json'); echo json_encode(['success'=>true]); exit; }
    redirect('site-faq.php?saved=1');
}

$faqs = $db->query('SELECT * FROM faq_items ORDER BY sort_order ASC')->fetchAll();
$page_title = 'FAQ Page';
$active_nav = 'site-faq';
require '_layout.php';
?>
<?php if (isset($_GET['saved'])): ?><div class="alert alert-success">Saved.</div><?php endif; ?>

<form method="POST" data-ajax>
<input type="hidden" name="action" value="save_settings">
<div class="form-section-title">Page Settings</div>
<div class="form-grid">
  <div class="form-group"><label>Meta Title</label><input type="text" name="faq_meta_title" value="<?= h(setting('faq_meta_title','FAQ')) ?>"></div>
  <div class="form-group full-width"><label>Hero Heading</label><input type="text" name="faq_hero_heading" value="<?= h(setting('faq_hero_heading','Frequently Asked Questions')) ?>"></div>
  <div class="form-group full-width"><label>Hero Subtext</label><textarea name="faq_hero_sub" rows="2"><?= h(setting('faq_hero_sub','Find answers to common questions about our campsite below.')) ?></textarea></div>
  <div class="form-group"><label>Section Heading</label><input type="text" name="faq_section_heading" value="<?= h(setting('faq_section_heading','Common Questions')) ?>"></div>
  <div class="form-group"><label>CTA Heading</label><input type="text" name="faq_cta_heading" value="<?= h(setting('faq_cta_heading','Still have questions?')) ?>"></div>
  <div class="form-group"><label>CTA Button</label><input type="text" name="faq_cta_btn" value="<?= h(setting('faq_cta_btn','Contact Us')) ?>"></div>
  <div class="form-group full-width"><label>CTA Text</label><input type="text" name="faq_cta_text" value="<?= h(setting('faq_cta_text','Our team is happy to help you plan the perfect camping trip.')) ?>"></div>
</div>
<div class="save-bar">
  <button type="submit" class="btn btn-primary">Save Settings</button>
  <span class="save-status" id="save-status"></span>
</div>
</form>

<div class="form-section-title" style="margin-top:24px;">FAQ Items</div>
<?php foreach ($faqs as $faq): ?>
<div class="item-block">
  <form method="POST">
    <input type="hidden" name="action" value="save_faq">
    <input type="hidden" name="id" value="<?= $faq['id'] ?>">
    <div class="item-block-header">
      <span class="item-block-title">FAQ #<?= $faq['id'] ?></span>
      <button type="button" class="item-remove" onclick="this.closest('.item-block').querySelector('form[data-delete]').submit()">&#10005;</button>
    </div>
    <div class="form-group"><label>Question</label><input type="text" name="question" value="<?= h($faq['question']) ?>"></div>
    <div class="form-group"><label>Answer</label><textarea name="answer" rows="3"><?= h($faq['answer']) ?></textarea></div>
    <div class="form-group" style="max-width:120px"><label>Sort Order</label><input type="number" name="sort_order" value="<?= $faq['sort_order'] ?>"></div>
    <button type="submit" class="btn btn-secondary btn-sm">Update</button>
  </form>
  <form method="POST" data-delete style="display:none"><input type="hidden" name="action" value="delete_faq"><input type="hidden" name="id" value="<?= $faq['id'] ?>"></form>
</div>
<?php endforeach; ?>

<div class="card" style="margin-top:12px;">
  <div class="card-header"><h2>Add FAQ Item</h2></div>
  <div class="card-body">
    <form method="POST">
      <input type="hidden" name="action" value="save_faq">
      <div class="form-group"><label>Question</label><input type="text" name="question" placeholder="What should I bring to camp?" required></div>
      <div class="form-group"><label>Answer</label><textarea name="answer" rows="3" placeholder="Your answer here..." required></textarea></div>
      <div class="form-group" style="max-width:120px"><label>Sort Order</label><input type="number" name="sort_order" value="<?= count($faqs) ?>"></div>
      <button type="submit" class="btn btn-primary">Add FAQ</button>
    </form>
  </div>
</div>

<?php require '_layout_end.php'; ?>
