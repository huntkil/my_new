<?php
session_start();
include "../../../system/includes/config.php";

if(!isset($_SESSION['id'])){
    echo "Please log in to access this page.";
    exit;
}

try {
    $db = Database::getInstance()->getConnection();
    
    // 기존 데이터 확인
    $countQuery = "SELECT COUNT(*) as count FROM myhealth";
    $countResult = $db->query($countQuery);
    $existingCount = $countResult->fetch()['count'];
    
    // 대규모 테스트 데이터 배열 (2023년 1월부터 2024년 12월까지)
    $testData = [];
    
    // 2023년 데이터 (다양한 패턴)
    $days2023 = [
        // 1월 - 겨울철 운동 (적음)
        ['2023', '1', '15', 'Sunday', 25, 7.0],
        ['2023', '1', '18', 'Wednesday', 30, 7.5],
        ['2023', '1', '22', 'Sunday', 35, 8.0],
        ['2023', '1', '25', 'Wednesday', 40, 8.2],
        ['2023', '1', '29', 'Sunday', 45, 8.5],
        
        // 2월 - 점진적 증가
        ['2023', '2', '1', 'Wednesday', 35, 8.0],
        ['2023', '2', '5', 'Sunday', 40, 8.3],
        ['2023', '2', '8', 'Wednesday', 45, 8.5],
        ['2023', '2', '12', 'Sunday', 50, 8.8],
        ['2023', '2', '15', 'Wednesday', 45, 8.6],
        ['2023', '2', '19', 'Sunday', 55, 9.0],
        ['2023', '2', '22', 'Wednesday', 50, 8.9],
        ['2023', '2', '26', 'Sunday', 60, 9.2],
        
        // 3월 - 봄철 운동 증가
        ['2023', '3', '1', 'Wednesday', 45, 8.5],
        ['2023', '3', '5', 'Sunday', 55, 9.0],
        ['2023', '3', '8', 'Wednesday', 50, 8.8],
        ['2023', '3', '12', 'Sunday', 60, 9.3],
        ['2023', '3', '15', 'Wednesday', 55, 9.1],
        ['2023', '3', '19', 'Sunday', 65, 9.5],
        ['2023', '3', '22', 'Wednesday', 60, 9.4],
        ['2023', '3', '26', 'Sunday', 70, 9.8],
        ['2023', '3', '29', 'Wednesday', 65, 9.6],
        
        // 4월 - 안정적 운동
        ['2023', '4', '2', 'Sunday', 60, 9.2],
        ['2023', '4', '5', 'Wednesday', 55, 9.0],
        ['2023', '4', '9', 'Sunday', 65, 9.5],
        ['2023', '4', '12', 'Wednesday', 60, 9.3],
        ['2023', '4', '16', 'Sunday', 70, 9.7],
        ['2023', '4', '19', 'Wednesday', 65, 9.5],
        ['2023', '4', '23', 'Sunday', 75, 10.0],
        ['2023', '4', '26', 'Wednesday', 70, 9.8],
        ['2023', '4', '30', 'Sunday', 80, 10.2],
        
        // 5월 - 최고 성과
        ['2023', '5', '3', 'Wednesday', 65, 9.5],
        ['2023', '5', '7', 'Sunday', 75, 10.0],
        ['2023', '5', '10', 'Wednesday', 70, 9.8],
        ['2023', '5', '14', 'Sunday', 80, 10.3],
        ['2023', '5', '17', 'Wednesday', 75, 10.1],
        ['2023', '5', '21', 'Sunday', 85, 10.5],
        ['2023', '5', '24', 'Wednesday', 80, 10.3],
        ['2023', '5', '28', 'Sunday', 90, 10.8],
        ['2023', '5', '31', 'Wednesday', 85, 10.6],
        
        // 6월 - 여름철 운동
        ['2023', '6', '4', 'Sunday', 75, 10.0],
        ['2023', '6', '7', 'Wednesday', 70, 9.8],
        ['2023', '6', '11', 'Sunday', 80, 10.3],
        ['2023', '6', '14', 'Wednesday', 75, 10.1],
        ['2023', '6', '18', 'Sunday', 85, 10.6],
        ['2023', '6', '21', 'Wednesday', 80, 10.4],
        ['2023', '6', '25', 'Sunday', 90, 10.9],
        ['2023', '6', '28', 'Wednesday', 85, 10.7],
        
        // 7월 - 더운 여름 (약간 감소)
        ['2023', '7', '2', 'Sunday', 70, 9.5],
        ['2023', '7', '5', 'Wednesday', 65, 9.3],
        ['2023', '7', '9', 'Sunday', 75, 9.8],
        ['2023', '7', '12', 'Wednesday', 70, 9.6],
        ['2023', '7', '16', 'Sunday', 80, 10.1],
        ['2023', '7', '19', 'Wednesday', 75, 9.9],
        ['2023', '7', '23', 'Sunday', 85, 10.4],
        ['2023', '7', '26', 'Wednesday', 80, 10.2],
        ['2023', '7', '30', 'Sunday', 90, 10.7],
        
        // 8월 - 여름 후반
        ['2023', '8', '2', 'Wednesday', 75, 9.8],
        ['2023', '8', '6', 'Sunday', 80, 10.2],
        ['2023', '8', '9', 'Wednesday', 75, 10.0],
        ['2023', '8', '13', 'Sunday', 85, 10.5],
        ['2023', '8', '16', 'Wednesday', 80, 10.3],
        ['2023', '8', '20', 'Sunday', 90, 10.8],
        ['2023', '8', '23', 'Wednesday', 85, 10.6],
        ['2023', '8', '27', 'Sunday', 95, 11.0],
        ['2023', '8', '30', 'Wednesday', 90, 10.8],
        
        // 9월 - 가을 시작
        ['2023', '9', '3', 'Sunday', 80, 10.2],
        ['2023', '9', '6', 'Wednesday', 75, 10.0],
        ['2023', '9', '10', 'Sunday', 85, 10.5],
        ['2023', '9', '13', 'Wednesday', 80, 10.3],
        ['2023', '9', '17', 'Sunday', 90, 10.8],
        ['2023', '9', '20', 'Wednesday', 85, 10.6],
        ['2023', '9', '24', 'Sunday', 95, 11.1],
        ['2023', '9', '27', 'Wednesday', 90, 10.9],
        
        // 10월 - 가을 운동
        ['2023', '10', '1', 'Sunday', 85, 10.4],
        ['2023', '10', '4', 'Wednesday', 80, 10.2],
        ['2023', '10', '8', 'Sunday', 90, 10.7],
        ['2023', '10', '11', 'Wednesday', 85, 10.5],
        ['2023', '10', '15', 'Sunday', 95, 11.0],
        ['2023', '10', '18', 'Wednesday', 90, 10.8],
        ['2023', '10', '22', 'Sunday', 100, 11.3],
        ['2023', '10', '25', 'Wednesday', 95, 11.1],
        ['2023', '10', '29', 'Sunday', 105, 11.5],
        
        // 11월 - 겨울 준비
        ['2023', '11', '1', 'Wednesday', 90, 10.8],
        ['2023', '11', '5', 'Sunday', 95, 11.2],
        ['2023', '11', '8', 'Wednesday', 90, 11.0],
        ['2023', '11', '12', 'Sunday', 100, 11.5],
        ['2023', '11', '15', 'Wednesday', 95, 11.3],
        ['2023', '11', '19', 'Sunday', 105, 11.8],
        ['2023', '11', '22', 'Wednesday', 100, 11.6],
        ['2023', '11', '26', 'Sunday', 110, 12.0],
        ['2023', '11', '29', 'Wednesday', 105, 11.8],
        
        // 12월 - 겨울철 (약간 감소)
        ['2023', '12', '3', 'Sunday', 95, 11.2],
        ['2023', '12', '6', 'Wednesday', 90, 11.0],
        ['2023', '12', '10', 'Sunday', 100, 11.5],
        ['2023', '12', '13', 'Wednesday', 95, 11.3],
        ['2023', '12', '17', 'Sunday', 105, 11.8],
        ['2023', '12', '20', 'Wednesday', 100, 11.6],
        ['2023', '12', '24', 'Sunday', 110, 12.1],
        ['2023', '12', '27', 'Wednesday', 105, 11.9],
        ['2023', '12', '31', 'Sunday', 115, 12.3]
    ];
    
    // 2024년 데이터 (더 다양한 패턴)
    $days2024 = [
        // 1월 - 새해 결심 (연속 운동)
        ['2024', '1', '1', 'Monday', 60, 9.0],
        ['2024', '1', '2', 'Tuesday', 65, 9.2],
        ['2024', '1', '3', 'Wednesday', 70, 9.5],
        ['2024', '1', '4', 'Thursday', 75, 9.8],
        ['2024', '1', '5', 'Friday', 80, 10.0],
        ['2024', '1', '6', 'Saturday', 85, 10.3],
        ['2024', '1', '7', 'Sunday', 90, 10.5],
        ['2024', '1', '8', 'Monday', 85, 10.3],
        ['2024', '1', '9', 'Tuesday', 80, 10.1],
        ['2024', '1', '10', 'Wednesday', 75, 9.9],
        ['2024', '1', '11', 'Thursday', 70, 9.7],
        ['2024', '1', '12', 'Friday', 65, 9.5],
        ['2024', '1', '13', 'Saturday', 60, 9.3],
        ['2024', '1', '14', 'Sunday', 55, 9.1],
        
        // 2월 - 겨울철 운동 (간헐적)
        ['2024', '2', '1', 'Thursday', 50, 8.5],
        ['2024', '2', '5', 'Monday', 55, 8.8],
        ['2024', '2', '10', 'Saturday', 60, 9.0],
        ['2024', '2', '15', 'Thursday', 65, 9.3],
        ['2024', '2', '20', 'Tuesday', 70, 9.6],
        ['2024', '2', '25', 'Sunday', 75, 9.9],
        ['2024', '2', '28', 'Wednesday', 80, 10.2],
        
        // 3월 - 봄 시작 (점진적 증가)
        ['2024', '3', '1', 'Friday', 75, 9.8],
        ['2024', '3', '5', 'Tuesday', 80, 10.1],
        ['2024', '3', '10', 'Sunday', 85, 10.4],
        ['2024', '3', '15', 'Friday', 90, 10.7],
        ['2024', '3', '20', 'Wednesday', 95, 11.0],
        ['2024', '3', '25', 'Monday', 100, 11.3],
        ['2024', '3', '30', 'Saturday', 105, 11.6],
        
        // 4월 - 안정적 운동
        ['2024', '4', '1', 'Monday', 100, 11.2],
        ['2024', '4', '5', 'Friday', 95, 11.0],
        ['2024', '4', '10', 'Wednesday', 90, 10.8],
        ['2024', '4', '15', 'Monday', 85, 10.6],
        ['2024', '4', '20', 'Saturday', 80, 10.4],
        ['2024', '4', '25', 'Thursday', 75, 10.2],
        ['2024', '4', '30', 'Tuesday', 70, 10.0],
        
        // 5월 - 최고 성과 달성
        ['2024', '5', '1', 'Wednesday', 65, 9.8],
        ['2024', '5', '5', 'Sunday', 70, 10.1],
        ['2024', '5', '10', 'Friday', 75, 10.4],
        ['2024', '5', '15', 'Wednesday', 80, 10.7],
        ['2024', '5', '20', 'Monday', 85, 11.0],
        ['2024', '5', '25', 'Saturday', 90, 11.3],
        ['2024', '5', '30', 'Thursday', 95, 11.6],
        
        // 6월 - 여름 준비
        ['2024', '6', '1', 'Saturday', 90, 11.2],
        ['2024', '6', '5', 'Wednesday', 85, 11.0],
        ['2024', '6', '10', 'Monday', 80, 10.8],
        ['2024', '6', '15', 'Saturday', 75, 10.6],
        ['2024', '6', '20', 'Thursday', 70, 10.4],
        ['2024', '6', '25', 'Tuesday', 65, 10.2],
        ['2024', '6', '30', 'Sunday', 60, 10.0],
        
        // 7월 - 여름철 운동
        ['2024', '7', '1', 'Monday', 55, 9.8],
        ['2024', '7', '5', 'Friday', 60, 10.1],
        ['2024', '7', '10', 'Wednesday', 65, 10.4],
        ['2024', '7', '15', 'Monday', 70, 10.7],
        ['2024', '7', '20', 'Saturday', 75, 11.0],
        ['2024', '7', '25', 'Thursday', 80, 11.3],
        ['2024', '7', '30', 'Tuesday', 85, 11.6],
        
        // 8월 - 여름 후반
        ['2024', '8', '1', 'Thursday', 80, 11.2],
        ['2024', '8', '5', 'Monday', 75, 11.0],
        ['2024', '8', '10', 'Saturday', 70, 10.8],
        ['2024', '8', '15', 'Thursday', 65, 10.6],
        ['2024', '8', '20', 'Tuesday', 60, 10.4],
        ['2024', '8', '25', 'Sunday', 55, 10.2],
        ['2024', '8', '30', 'Friday', 50, 10.0],
        
        // 9월 - 가을 시작
        ['2024', '9', '1', 'Sunday', 45, 9.8],
        ['2024', '9', '5', 'Thursday', 50, 10.1],
        ['2024', '9', '10', 'Tuesday', 55, 10.4],
        ['2024', '9', '15', 'Sunday', 60, 10.7],
        ['2024', '9', '20', 'Friday', 65, 11.0],
        ['2024', '9', '25', 'Wednesday', 70, 11.3],
        ['2024', '9', '30', 'Monday', 75, 11.6],
        
        // 10월 - 가을 운동
        ['2024', '10', '1', 'Tuesday', 70, 11.2],
        ['2024', '10', '5', 'Saturday', 65, 11.0],
        ['2024', '10', '10', 'Thursday', 60, 10.8],
        ['2024', '10', '15', 'Tuesday', 55, 10.6],
        ['2024', '10', '20', 'Sunday', 50, 10.4],
        ['2024', '10', '25', 'Friday', 45, 10.2],
        ['2024', '10', '30', 'Wednesday', 40, 10.0],
        
        // 11월 - 겨울 준비
        ['2024', '11', '1', 'Friday', 35, 9.8],
        ['2024', '11', '5', 'Tuesday', 40, 10.1],
        ['2024', '11', '10', 'Sunday', 45, 10.4],
        ['2024', '11', '15', 'Friday', 50, 10.7],
        ['2024', '11', '20', 'Wednesday', 55, 11.0],
        ['2024', '11', '25', 'Monday', 60, 11.3],
        ['2024', '11', '30', 'Saturday', 65, 11.6],
        
        // 12월 - 겨울철 (현재)
        ['2024', '12', '1', 'Sunday', 60, 11.2],
        ['2024', '12', '5', 'Thursday', 55, 11.0],
        ['2024', '12', '10', 'Tuesday', 50, 10.8],
        ['2024', '12', '15', 'Sunday', 45, 10.6],
        ['2024', '12', '20', 'Friday', 40, 10.4],
        ['2024', '12', '25', 'Wednesday', 35, 10.2],
        ['2024', '12', '30', 'Monday', 30, 10.0]
    ];
    
    $testData = array_merge($days2023, $days2024);
    
    $stmt = $db->prepare("INSERT INTO myhealth (year, month, day, dayofweek, running_time, running_speed_start) VALUES (?, ?, ?, ?, ?, ?)");
    
    $insertedCount = 0;
    foreach ($testData as $data) {
        if ($stmt->execute($data)) {
            $insertedCount++;
        }
    }
    
    // 최종 통계 계산
    $finalCountQuery = "SELECT COUNT(*) as count FROM myhealth";
    $finalCountResult = $db->query($finalCountQuery);
    $finalCount = $finalCountResult->fetch()['count'];
    
    echo "<div style='font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 30px; border: 1px solid #ddd; border-radius: 12px; background: #f9f9f9;'>";
    echo "<h2 style='color: #333; margin-bottom: 20px;'>🎉 Extensive Test Data Added Successfully!</h2>";
    
    echo "<div style='background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px;'>";
    echo "<h3 style='color: #2563eb; margin-bottom: 15px;'>📊 Data Summary</h3>";
    echo "<ul style='list-style: none; padding: 0;'>";
    echo "<li style='padding: 8px 0; border-bottom: 1px solid #eee;'><strong>Previously had:</strong> {$existingCount} records</li>";
    echo "<li style='padding: 8px 0; border-bottom: 1px solid #eee;'><strong>Newly added:</strong> {$insertedCount} records</li>";
    echo "<li style='padding: 8px 0; border-bottom: 1px solid #eee;'><strong>Total records now:</strong> {$finalCount} records</li>";
    echo "<li style='padding: 8px 0; border-bottom: 1px solid #eee;'><strong>Total pages:</strong> " . ceil($finalCount / 10) . " pages</li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<div style='background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px;'>";
    echo "<h3 style='color: #059669; margin-bottom: 15px;'>📈 Test Patterns Included</h3>";
    echo "<ul style='list-style: none; padding: 0;'>";
    echo "<li style='padding: 5px 0;'>✅ 2년간의 데이터 (2023-2024)</li>";
    echo "<li style='padding: 5px 0;'>✅ 계절별 운동 패턴 변화</li>";
    echo "<li style='padding: 5px 0;'>✅ 연속 운동 일수 (최대 14일)</li>";
    echo "<li style='padding: 5px 0;'>✅ 요일별 운동 빈도 차이</li>";
    echo "<li style='padding: 5px 0;'>✅ 점진적 성능 향상</li>";
    echo "<li style='padding: 5px 0;'>✅ 최고 기록과 최저 기록</li>";
    echo "<li style='padding: 5px 0;'>✅ 월별 활동량 변화</li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<div style='display: flex; gap: 15px; flex-wrap: wrap;'>";
    echo "<a href='health_list.php' style='display: inline-block; background: #2563eb; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 500;'>📋 View Health List</a>";
    echo "<a href='health_stats.php' style='display: inline-block; background: #059669; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 500;'>📊 View Statistics</a>";
    echo "</div>";
    
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div style='font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; border: 1px solid #ff6b6b; border-radius: 8px; background: #ffe6e6;'>";
    echo "<h2 style='color: #d63031;'>❌ Error</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "</div>";
} finally {
    if (isset($stmt)) {
        $stmt->closeCursor();
    }
}
?> 