<?php
// admin/delete.php
require "admin-auth.php"; 
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    if ($id) {
        // fetch image name to delete file
        $stmt = $con->prepare("SELECT blog_image FROM blogs WHERE id=?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $img = $res['blog_image'] ?? null;

        $stmt = $con->prepare("DELETE FROM blogs WHERE id=?");
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            if ($img && file_exists(__DIR__ . '/../uploads/blog/' . $img)) {
                @unlink(__DIR__ . '/../uploads/blog/' . $img);
            }
        }
    }
}
header('Location: test.php');
exit;
