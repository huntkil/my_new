<?php
session_start();
include "../../../system/includes/config.php";

if(!isset($_SESSION['id'])){
    echo "Please log in to access this page.";
    exit;
}

try {
    $db = Database::getInstance()->getConnection();
    
    // 테스트 데이터 배열
    $testData = [
        ['2024', '1', '15', 'Monday', 30, 8.5],
        ['2024', '1', '16', 'Tuesday', 45, 9.0],
        ['2024', '1', '17', 'Wednesday', 35, 8.0],
        ['2024', '1', '18', 'Thursday', 50, 9.5],
        ['2024', '1', '19', 'Friday', 40, 8.8],
        ['2024', '1', '20', 'Saturday', 60, 10.0],
        ['2024', '1', '21', 'Sunday', 25, 7.5],
        ['2024', '1', '22', 'Monday', 35, 8.2],
        ['2024', '1', '23', 'Tuesday', 45, 9.2],
        ['2024', '1', '24', 'Wednesday', 30, 8.0],
        ['2024', '1', '25', 'Thursday', 55, 9.8],
        ['2024', '1', '26', 'Friday', 40, 8.5],
        ['2024', '1', '27', 'Saturday', 65, 10.2],
        ['2024', '1', '28', 'Sunday', 30, 7.8],
        ['2024', '1', '29', 'Monday', 40, 8.7],
        ['2024', '1', '30', 'Tuesday', 50, 9.3],
        ['2024', '1', '31', 'Wednesday', 35, 8.1],
        ['2024', '2', '1', 'Thursday', 45, 9.1],
        ['2024', '2', '2', 'Friday', 55, 9.7],
        ['2024', '2', '3', 'Saturday', 70, 10.5],
        ['2024', '2', '4', 'Sunday', 25, 7.3],
        ['2024', '2', '5', 'Monday', 40, 8.6],
        ['2024', '2', '6', 'Tuesday', 50, 9.4],
        ['2024', '2', '7', 'Wednesday', 35, 8.3],
        ['2024', '2', '8', 'Thursday', 45, 9.0]
    ];
    
    $stmt = $db->prepare("INSERT INTO myhealth (year, month, day, dayofweek, running_time, running_speed_start) VALUES (?, ?, ?, ?, ?, ?)");
    
    $insertedCount = 0;
    foreach ($testData as $data) {
        if ($stmt->execute($data)) {
            $insertedCount++;
        }
    }
    
    echo "<div style='font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;'>";
    echo "<h2 style='color: #333;'>Test Data Added Successfully!</h2>";
    echo "<p><strong>Inserted:</strong> {$insertedCount} records</p>";
    echo "<p><strong>Total records:</strong> " . ($insertedCount + count($testData)) . "</p>";
    echo "<p>This will create " . ceil(($insertedCount + count($testData)) / 10) . " pages of pagination.</p>";
    echo "<a href='health_list.php' style='display: inline-block; background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 20px;'>View Health List</a>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div style='font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; border: 1px solid #ff6b6b; border-radius: 8px; background: #ffe6e6;'>";
    echo "<h2 style='color: #d63031;'>Error</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "</div>";
} finally {
    if (isset($stmt)) {
        $stmt->closeCursor();
    }
}
?> 