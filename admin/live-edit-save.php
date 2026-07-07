<?php
require_once dirname(__DIR__) . '/config.php';
require_admin_login();

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    echo json_encode(['success' => false, 'error' => 'Invalid payload']);
    exit;
}

$db   = get_db();
$type = $data['type'] ?? '';

function clean(string $html): string {
    // Strip script/iframe tags but preserve inline formatting
    $html = preg_replace('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/i', '', $html);
    $html = preg_replace('/<iframe\b[^<]*(?:(?!<\/iframe>)<[^<]*)*<\/iframe>/i', '', $html);
    // Decode any HTML entities so repeated saves don't accumulate &amp;amp; etc.
    $html = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    return trim($html);
}

try {
    switch ($type) {
        case 'setting':
            $key = preg_replace('/[^a-z0-9_]/', '', $data['key'] ?? '');
            if (!$key) throw new Exception('Invalid key');
            save_setting($key, clean($data['value'] ?? ''));
            break;

        case 'feature':
        case 'space':
        case 'amenity':
        case 'pricing':
            $id    = (int)($data['id'] ?? 0);
            $field = preg_replace('/[^a-z0-9_]/', '', $data['field'] ?? '');
            if (!$id || !$field) throw new Exception('Invalid id/field');

            $table_map = [
                'feature'  => 'features',
                'space'    => 'spaces',
                'amenity'  => 'amenities',
                'pricing'  => 'pricing_plans',
            ];
            $table = $table_map[$type];

            // Whitelist allowed fields per table
            $allowed = [
                'features'      => ['icon','title','description','stat1_num','stat1_label','stat2_num','stat2_label','stat3_num','stat3_label'],
                'spaces'        => ['title','description','tag1','tag2','tag3'],
                'amenities'     => ['icon','title','description'],
                'pricing_plans' => ['name','button_text'],
            ];
            if (!in_array($field, $allowed[$table] ?? [])) throw new Exception('Field not allowed');

            $stmt = $db->prepare("UPDATE {$table} SET {$field} = ? WHERE id = ?");
            $stmt->execute([clean($data['value'] ?? ''), $id]);
            break;

        case 'pricing-feat':
            $id       = (int)($data['id'] ?? 0);
            $features = array_map('clean', $data['features'] ?? []);
            if (!$id) throw new Exception('Invalid id');
            $stmt = $db->prepare('UPDATE pricing_plans SET features = ? WHERE id = ?');
            $stmt->execute([json_encode($features), $id]);
            break;

        case 'image_src':
            $key = preg_replace('/[^a-z0-9_]/', '', $data['key'] ?? '');
            if (!$key) throw new Exception('Invalid key');
            $src = $data['value'] ?? '';
            // Strip leading ../ so path is stored as new_images/file.jpg or uploads/file.jpg
            $src = preg_replace('/^\.\.\//', '', $src);
            if (!preg_match('/^(new_images|uploads)\/[^\/]+$/', $src)) {
                throw new Exception('Invalid image path');
            }
            save_setting('img_src_' . $key, $src);
            break;

        default:
            throw new Exception('Unknown type: ' . $type);
    }

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
