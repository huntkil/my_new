<?php
/**
 * My Playground 배포 빌드 스크립트
 * 카페24 호스팅 환경을 위한 배포 준비
 */

class DeployBuilder {
    private $version;
    private $build_date;
    private $excludes = [
        '.git',
        '.gitignore',
        'README*.md',
        'deploy/',
        'admin/system_check.php',
        'database_schema.sql',
        '.DS_Store',
        'Thumbs.db',
        '*.tmp',
        '*.log'
    ];
    
    public function __construct() {
        $this->version = $this->generateVersion();
        $this->build_date = date('Y-m-d H:i:s');
        
        echo "=== My Playground 배포 빌드 시작 ===\n";
        echo "버전: {$this->version}\n";
        echo "빌드 시간: {$this->build_date}\n\n";
    }
    
    /**
     * 버전 생성 (YYYY.MM.DD.BUILD)
     */
    private function generateVersion() {
        $date_part = date('Y.m.d');
        $build_number = 1;
        
        // 기존 버전 파일이 있다면 빌드 번호 증가
        if (file_exists('../version.json')) {
            $version_info = json_decode(file_get_contents('../version.json'), true);
            if ($version_info && isset($version_info['build_date'])) {
                if (date('Y-m-d') === date('Y-m-d', strtotime($version_info['build_date']))) {
                    $build_number = ($version_info['build_number'] ?? 0) + 1;
                }
            }
        }
        
        return "{$date_part}.{$build_number}";
    }
    
    /**
     * 빌드 실행
     */
    public function build() {
        echo "1. 버전 정보 파일 생성...\n";
        $this->createVersionFile();
        
        echo "2. 디렉토리 정리...\n";
        $this->cleanDirectories();
        
        echo "3. 파일 권한 검사...\n";
        $this->checkPermissions();
        
        echo "4. 필수 파일 검사...\n";
        $this->checkRequiredFiles();
        
        echo "5. 배포 패키지 생성...\n";
        $this->createDeployPackage();
        
        echo "\n=== 빌드 완료 ===\n";
        echo "배포 패키지: deploy/my_playground_v{$this->version}.zip\n";
    }
    
    /**
     * 버전 정보 파일 생성
     */
    private function createVersionFile() {
        $version_info = [
            'version' => $this->version,
            'build_date' => $this->build_date,
            'build_number' => (int)explode('.', $this->version)[3],
            'php_version' => PHP_VERSION,
            'environment' => 'production',
            'features' => [
                'word_cards' => true,
                'vocabulary' => true,
                'news_search' => true,
                'health_tracker' => true,
                'box_breathing' => true,
                'family_tour' => true,
                'crud_demo' => true,
                'user_auth' => true
            ]
        ];
        
        file_put_contents('../version.json', json_encode($version_info, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        echo "   ✓ version.json 생성 완료\n";
    }
    
    /**
     * 디렉토리 정리
     */
    private function cleanDirectories() {
        $directories = ['../uploads', '../logs'];
        
        foreach ($directories as $dir) {
            if (!file_exists($dir)) {
                mkdir($dir, 0755, true);
                echo "   ✓ {$dir} 디렉토리 생성\n";
            }
            
            // .gitkeep 파일 생성 (빈 디렉토리 유지)
            $gitkeep = $dir . '/.gitkeep';
            if (!file_exists($gitkeep)) {
                file_put_contents($gitkeep, '');
                echo "   ✓ {$gitkeep} 생성\n";
            }
        }
    }
    
    /**
     * 파일 권한 검사
     */
    private function checkPermissions() {
        $checks = [
            '../uploads' => '755',
            '../logs' => '755',
            '../includes/config.php' => '644',
            '../.htaccess' => '644'
        ];
        
        foreach ($checks as $file => $expected) {
            if (file_exists($file)) {
                $current = substr(sprintf('%o', fileperms($file)), -3);
                if ($current === $expected) {
                    echo "   ✓ {$file}: {$current}\n";
                } else {
                    echo "   ⚠ {$file}: {$current} (권장: {$expected})\n";
                }
            } else {
                echo "   ✗ {$file}: 파일이 존재하지 않음\n";
            }
        }
    }
    
    /**
     * 필수 파일 검사
     */
    private function checkRequiredFiles() {
        $required_files = [
            '../index.html',
            '../includes/config.php',
            '../includes/Database.php',
            '../includes/header.php',
            '../includes/footer.php',
            '../.htaccess'
        ];
        
        foreach ($required_files as $file) {
            if (file_exists($file)) {
                echo "   ✓ {$file}\n";
            } else {
                echo "   ✗ {$file}: 필수 파일이 없습니다!\n";
                exit(1);
            }
        }
    }
    
    /**
     * 배포 패키지 생성
     */
    private function createDeployPackage() {
        if (!extension_loaded('zip')) {
            echo "   ✗ ZIP 확장이 필요합니다.\n";
            return;
        }
        
        $zip_filename = "my_playground_v{$this->version}.zip";
        $zip_path = __DIR__ . '/' . $zip_filename;
        
        $zip = new ZipArchive();
        if ($zip->open($zip_path, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            echo "   ✗ ZIP 파일을 생성할 수 없습니다.\n";
            return;
        }
        
        $this->addDirectoryToZip($zip, '..', '');
        $zip->close();
        
        echo "   ✓ {$zip_filename} 생성 완료 (" . $this->formatBytes(filesize($zip_path)) . ")\n";
        
        // 배포 가이드 생성
        $this->createDeployGuide();
    }
    
    /**
     * 디렉토리를 ZIP에 추가
     */
    private function addDirectoryToZip($zip, $source, $destination) {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($source),
            RecursiveIteratorIterator::SELF_FIRST
        );
        
        foreach ($iterator as $file) {
            $filename = $file->getFilename();
            
            // 제외 파일 체크
            if ($this->shouldExclude($file->getPathname())) {
                continue;
            }
            
            if ($file->isDir()) {
                $relativePath = $destination . substr($file->getPathname(), strlen($source) + 1) . '/';
                $zip->addEmptyDir($relativePath);
            } elseif ($file->isFile()) {
                $relativePath = $destination . substr($file->getPathname(), strlen($source) + 1);
                $zip->addFile($file->getPathname(), $relativePath);
            }
        }
    }
    
    /**
     * 파일 제외 여부 확인
     */
    private function shouldExclude($path) {
        foreach ($this->excludes as $exclude) {
            if (fnmatch($exclude, basename($path)) || 
                strpos($path, str_replace('*', '', $exclude)) !== false) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * 파일 크기 포맷
     */
    private function formatBytes($size) {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }
        return round($size, 2) . ' ' . $units[$i];
    }
    
    /**
     * 배포 가이드 생성
     */
    private function createDeployGuide() {
        $guide = "# My Playground v{$this->version} 배포 가이드

## 📦 배포 패키지 정보
- 버전: {$this->version}
- 빌드 날짜: {$this->build_date}
- PHP 요구사항: PHP 7.4+
- 데이터베이스: MySQL 5.7+ / MariaDB 10.2+

## 🚀 카페24 호스팅 배포 단계

### 1. 파일 업로드
1. FTP 클라이언트로 카페24 서버 접속 (huntkil.cafe24.com)
2. /public_html/ 디렉토리에 모든 파일 업로드
3. 파일 권한 설정:
   - 디렉토리: 755
   - PHP 파일: 644
   - uploads/, logs/: 755

### 2. 데이터베이스 설정
1. 카페24 DB 관리에서 데이터베이스 생성 (huntkil)
2. phpMyAdmin 접속
3. database_schema.sql 파일 내용 실행
4. 관리자 계정 비밀번호 변경

### 3. 설정 확인
1. config.php의 도메인 설정 확인 (http://gukho.net)
2. .htaccess 파일 업로드 확인
3. 파일 권한 설정 확인
4. 시스템 상태 확인: http://gukho.net/admin/system_check.php

### 4. 테스트
1. 홈페이지 접속 테스트
2. 로그인 기능 테스트
3. 각 모듈 동작 확인

## 🔧 문제 해결
- 데이터베이스 연결 오류 → DB 정보 확인
- 파일 업로드 오류 → 권한 설정 확인
- 세션 오류 → 쿠키/도메인 설정 확인

## 📞 지원
- 카페24 고객센터: 1588-5835
- 문서: README_SETUP.md

---
빌드 시간: {$this->build_date}
";
        
        file_put_contents(__DIR__ . '/DEPLOY_GUIDE.md', $guide);
        echo "   ✓ DEPLOY_GUIDE.md 생성 완료\n";
    }
}

// 스크립트 실행
if (php_sapi_name() === 'cli') {
    $builder = new DeployBuilder();
    $builder->build();
} else {
    echo "이 스크립트는 명령줄에서만 실행할 수 있습니다.\n";
    echo "사용법: php build.php\n";
}
?> 