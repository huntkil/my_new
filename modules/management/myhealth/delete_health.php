<?php
session_start();
$page_title = "Delete Health Record";
include "../../../system/includes/header.php";

if(!isset($_SESSION['id'])){
    echo '<div class="container mx-auto px-4 py-8">';
    echo '<div class="bg-destructive/15 text-destructive rounded-lg p-4 text-center">';
    echo "Please log in to access this page.";
    echo '</div></div>';
    echo '<script>setTimeout(function(){ window.location.href = "../../../system/auth/login.php"; }, 2000);</script>';
    echo '</body></html>';
    exit;
}

// 데이터베이스 연결
include "../../../system/includes/config.php";
include "../../../system/includes/Database.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    try {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("DELETE FROM myhealth WHERE no = ?");
        
        if ($stmt->execute([$id])) {
            header("Location: health_list.php");
            exit;
        } else {
            throw new Exception("Failed to delete record");
        }
    } catch (Exception $e) {
        echo '<div class="container mx-auto px-4 py-8">';
        echo '<div class="bg-destructive/15 text-destructive rounded-lg p-4">';
        echo "Error: " . $e->getMessage();
        echo '</div></div>';
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
    }
} else {
    echo '<div class="container mx-auto px-4 py-8">';
    echo '<div class="bg-destructive/15 text-destructive rounded-lg p-4">';
    echo "Invalid record ID";
    echo '</div></div>';
}

include "../../../system/includes/footer.php";
?> 