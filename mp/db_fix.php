<?php
// DB 테이블 구조 확인 및 수정 스크립트
require_once 'system/includes/config_production.php';

echo "<h1>DB 테이블 구조 확인 및 수정</h1>";

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    
    echo "✅ DB 연결 성공<br><br>";
    
    // 1. 기존 테이블 구조 확인
    echo "<h2>1. 기존 테이블 구조 확인</h2>";
    
    // myUser 테이블이 존재하는지 확인
    $stmt = $pdo->query("SHOW TABLES LIKE 'myUser'");
    if ($stmt->rowCount() > 0) {
        echo "✅ myUser 테이블 존재<br>";
        
        // 테이블 구조 확인
        $stmt = $pdo->query("DESCRIBE myUser");
        $columns = $stmt->fetchAll();
        
        echo "<h3>현재 myUser 테이블 구조:</h3>";
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
        foreach ($columns as $col) {
            echo "<tr>";
            echo "<td>{$col['Field']}</td>";
            echo "<td>{$col['Type']}</td>";
            echo "<td>{$col['Null']}</td>";
            echo "<td>{$col['Key']}</td>";
            echo "<td>{$col['Default']}</td>";
            echo "</tr>";
        }
        echo "</table><br>";
        
        // 필요한 컬럼들 확인
        $required_columns = ['id', 'password', 'name', 'email', 'role', 'status', 'login_attempts', 'last_login', 'last_attempt', 'created_at', 'updated_at'];
        $existing_columns = array_column($columns, 'Field');
        
        echo "<h3>필요한 컬럼 확인:</h3>";
        foreach ($required_columns as $col) {
            if (in_array($col, $existing_columns)) {
                echo "✅ $col 컬럼 존재<br>";
            } else {
                echo "❌ $col 컬럼 없음 - 추가 필요<br>";
            }
        }
        
        // 2. 누락된 컬럼 추가
        echo "<h2>2. 누락된 컬럼 추가</h2>";
        
        if (!in_array('name', $existing_columns)) {
            $sql = "ALTER TABLE myUser ADD COLUMN name VARCHAR(100) NOT NULL DEFAULT 'User' AFTER password";
            $pdo->exec($sql);
            echo "✅ name 컬럼 추가 완료<br>";
        }
        
        if (!in_array('email', $existing_columns)) {
            $sql = "ALTER TABLE myUser ADD COLUMN email VARCHAR(100) DEFAULT NULL AFTER name";
            $pdo->exec($sql);
            echo "✅ email 컬럼 추가 완료<br>";
        }
        
        if (!in_array('role', $existing_columns)) {
            $sql = "ALTER TABLE myUser ADD COLUMN role ENUM('admin','user') DEFAULT 'user' AFTER email";
            $pdo->exec($sql);
            echo "✅ role 컬럼 추가 완료<br>";
        }
        
        if (!in_array('status', $existing_columns)) {
            $sql = "ALTER TABLE myUser ADD COLUMN status ENUM('active','inactive') DEFAULT 'active' AFTER role";
            $pdo->exec($sql);
            echo "✅ status 컬럼 추가 완료<br>";
        }
        
        if (!in_array('login_attempts', $existing_columns)) {
            $sql = "ALTER TABLE myUser ADD COLUMN login_attempts INT(11) DEFAULT 0 AFTER status";
            $pdo->exec($sql);
            echo "✅ login_attempts 컬럼 추가 완료<br>";
        }
        
        if (!in_array('last_login', $existing_columns)) {
            $sql = "ALTER TABLE myUser ADD COLUMN last_login DATETIME DEFAULT NULL AFTER login_attempts";
            $pdo->exec($sql);
            echo "✅ last_login 컬럼 추가 완료<br>";
        }
        
        if (!in_array('last_attempt', $existing_columns)) {
            $sql = "ALTER TABLE myUser ADD COLUMN last_attempt DATETIME DEFAULT NULL AFTER last_login";
            $pdo->exec($sql);
            echo "✅ last_attempt 컬럼 추가 완료<br>";
        }
        
        if (!in_array('created_at', $existing_columns)) {
            $sql = "ALTER TABLE myUser ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER last_attempt";
            $pdo->exec($sql);
            echo "✅ created_at 컬럼 추가 완료<br>";
        }
        
        if (!in_array('updated_at', $existing_columns)) {
            $sql = "ALTER TABLE myUser ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER created_at";
            $pdo->exec($sql);
            echo "✅ updated_at 컬럼 추가 완료<br>";
        }
        
    } else {
        echo "❌ myUser 테이블이 존재하지 않습니다.<br>";
        echo "db_setup.php를 먼저 실행해주세요.<br>";
    }
    
    // 3. 관리자 계정 생성/업데이트
    echo "<h2>3. 관리자 계정 생성/업데이트</h2>";
    
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
    
    // 기존 admin 계정 확인
    $stmt = $pdo->prepare("SELECT id FROM myUser WHERE id = 'admin'");
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        // 기존 계정 업데이트
        $sql = "UPDATE myUser SET 
                password = ?, 
                name = 'Administrator', 
                email = 'admin@example.com', 
                role = 'admin', 
                status = 'active',
                updated_at = CURRENT_TIMESTAMP 
                WHERE id = 'admin'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$admin_password]);
        echo "✅ 기존 관리자 계정 업데이트 완료 (ID: admin, PW: admin123)<br>";
    } else {
        // 새 계정 생성
        $sql = "INSERT INTO myUser (id, password, name, email, role, status) 
                VALUES ('admin', ?, 'Administrator', 'admin@example.com', 'admin', 'active')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$admin_password]);
        echo "✅ 새 관리자 계정 생성 완료 (ID: admin, PW: admin123)<br>";
    }
    
    echo "<h2>✅ DB 수정 완료!</h2>";
    echo "<p>이제 <a href='system/auth/login.php'>로그인 페이지</a>에서 테스트할 수 있습니다.</p>";
    echo "<p>관리자 로그인: ID: admin, PW: admin123</p>";
    
} catch (PDOException $e) {
    echo "❌ DB 오류: " . $e->getMessage() . "<br>";
    echo "오류 코드: " . $e->getCode() . "<br>";
}
?> 