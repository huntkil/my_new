# My Playground 개발 환경 설정 가이드

## 📋 목차
1. [로컬 개발 환경 설정](#로컬-개발-환경-설정)
2. [데이터베이스 설정](#데이터베이스-설정)
3. [카페24 호스팅 배포](#카페24-호스팅-배포)
4. [보안 설정](#보안-설정)
5. [문제 해결](#문제-해결)

## 🔧 로컬 개발 환경 설정

### 1. 필요한 소프트웨어 설치

#### Windows (XAMPP 사용)
```bash
# 1. XAMPP 다운로드 및 설치
# https://www.apachefriends.org/download.html

# 2. XAMPP 설치 후 Apache, MySQL 서비스 시작
# XAMPP Control Panel에서 Apache, MySQL 시작 버튼 클릭
```

#### macOS (MAMP 사용)
```bash
# 1. MAMP 다운로드 및 설치
# https://www.mamp.info/en/downloads/

# 2. MAMP 실행 후 서버 시작
# Start Servers 버튼 클릭
```

#### Linux (Ubuntu/Debian)
```bash
# Apache, PHP, MySQL 설치
sudo apt update
sudo apt install apache2 php php-mysql mysql-server php-mbstring php-xml php-curl

# Apache 모듈 활성화
sudo a2enmod rewrite
sudo systemctl restart apache2

# MySQL 보안 설정
sudo mysql_secure_installation
```

### 2. 프로젝트 설정

```bash
# 1. 프로젝트 클론/복사
# XAMPP: C:\xampp\htdocs\my_www
# MAMP: /Applications/MAMP/htdocs/my_www
# Linux: /var/www/html/my_www

# 2. 권한 설정 (Linux/macOS)
chmod -R 755 /var/www/html/my_www
chown -R www-data:www-data /var/www/html/my_www

# 3. 업로드 및 로그 디렉토리 생성
mkdir uploads logs
chmod 755 uploads logs
```

### 3. PHP 설정 (php.ini)

```ini
# 주요 설정 확인/수정
memory_limit = 128M
max_execution_time = 30
post_max_size = 8M
upload_max_filesize = 5M
max_file_uploads = 10
date.timezone = "Asia/Seoul"

# 에러 표시 (개발 환경에서만)
display_errors = On
log_errors = On
error_log = /path/to/your/error.log
```

## 🗄️ 데이터베이스 설정

### 1. MySQL/MariaDB 데이터베이스 생성

```sql
-- 데이터베이스 생성
CREATE DATABASE huntkil CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- 사용자 생성 (로컬 개발용)
CREATE USER 'huntkil'@'localhost' IDENTIFIED BY 'kil7310k4!';
GRANT ALL PRIVILEGES ON huntkil.* TO 'huntkil'@'localhost';
FLUSH PRIVILEGES;
```

### 2. 스키마 및 초기 데이터 설정

```bash
# 1. MySQL 접속
mysql -u huntkil -p huntkil

# 2. 스키마 파일 실행
mysql -u huntkil -p huntkil < database_schema.sql

# 또는 phpMyAdmin 사용
# localhost/phpmyadmin 접속 후 SQL 탭에서 database_schema.sql 내용 실행
```

### 3. 환경 설정 확인

```php
// config.php 파일에서 로컬 환경 설정 확인
// 로컬 개발 환경에서는 자동으로 로컬 설정이 적용됨
```

## 🚀 카페24 호스팅 배포

### 1. 배포 전 체크리스트

- [ ] 모든 파일 업로드 (FTP/SFTP)
- [ ] 데이터베이스 덤프 및 복원
- [ ] 파일 권한 설정 (755/644)
- [ ] 환경 변수 확인
- [ ] .htaccess 파일 업로드
- [ ] 에러 로그 확인

### 2. 파일 업로드 (FTP)

```bash
# FTP 클라이언트 설정
호스트: huntkil.cafe24.com
사용자명: huntkil (카페24 계정)
비밀번호: kil7310k4! (카페24 비밀번호)
포트: 21

# 업로드 대상 디렉토리
/public_html/ (또는 /www/)

# 업로드 제외 파일
.git/
.gitignore
README*.md
database_schema.sql (보안상 제외)
```

### 3. 데이터베이스 설정

```sql
-- 카페24 phpMyAdmin 접속
-- 1. 데이터베이스 생성 (huntkil)
-- 2. database_schema.sql 파일 내용 실행
-- 3. 관리자 계정 비밀번호 변경 (보안)

UPDATE myUser SET password = PASSWORD('new_secure_password') WHERE id = 'admin';
```

### 4. 파일 권한 설정

```bash
# 카페24 파일 매니저 또는 FTP 클라이언트에서 설정
디렉토리: 755
PHP 파일: 644
uploads/: 755
logs/: 755
.htaccess: 644
```

### 5. 도메인 설정

```php
// config.php에서 도메인 확인 (gukho.net HTTP로 설정됨)
define('APP_URL', 'http://gukho.net');
```

## 🔒 보안 설정

### 1. 필수 보안 설정

```php
// 1. 관리자 비밀번호 변경
// 기본값: admin / admin123
// 강력한 비밀번호로 변경 필요

// 2. API 키 설정
// News API 키 확인 및 갱신

// 3. 파일 권한 최적화
// 불필요한 파일 삭제
// 민감한 파일 접근 차단
```

### 2. .htaccess 보안 설정

```apache
# 이미 설정됨 (.htaccess 파일 참조)
# - 디렉토리 브라우징 비활성화
# - 민감한 파일 접근 차단
# - 보안 헤더 설정
# - 파일 업로드 보안
```

### 3. 추가 보안 조치

```php
// 1. 세션 보안
session_regenerate_id(true);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1); // HTTPS에서만

// 2. 입력 검증
$input = filter_input(INPUT_POST, 'data', FILTER_SANITIZE_STRING);
$email = filter_var($email, FILTER_VALIDATE_EMAIL);

// 3. SQL 인젝션 방지
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
```

## 🔧 문제 해결

### 1. 자주 발생하는 오류

#### 데이터베이스 연결 오류
```php
// 1. 데이터베이스 정보 확인
// 2. 사용자 권한 확인
// 3. 호스트 이름 확인 (localhost vs IP)
```

#### 파일 업로드 오류
```php
// 1. 파일 권한 확인 (uploads 디렉토리)
// 2. PHP 설정 확인 (upload_max_filesize)
// 3. 디렉토리 존재 여부 확인
```

#### 세션 오류
```php
// 1. 세션 디렉토리 권한 확인
// 2. PHP 세션 설정 확인
// 3. 도메인/쿠키 설정 확인
```

### 2. 로그 확인

```bash
# 에러 로그 위치
# 로컬: xampp/logs/error.log
# 카페24: logs/error.log

# 실시간 로그 확인 (Linux)
tail -f /var/log/apache2/error.log
tail -f logs/error.log
```

### 3. 성능 최적화

```apache
# .htaccess 설정 (이미 적용됨)
# - Gzip 압축
# - 브라우저 캐싱
# - 이미지 최적화
```

## 📞 지원 및 연락처

### 카페24 고객센터
- 전화: 1588-5835
- 이메일: help@cafe24.com
- 온라인: https://help.cafe24.com

### 개발 문의
- 프로젝트 관련 문의사항이 있으시면 GitHub Issues 또는 이메일로 연락해주세요.

---

## 🎯 배포 후 확인사항

1. **기본 기능 테스트**
   - [ ] 홈페이지 접속 확인
   - [ ] 로그인/로그아웃 기능
   - [ ] 각 모듈 정상 동작 확인

2. **보안 테스트**
   - [ ] 관리자 계정 비밀번호 변경
   - [ ] 민감한 파일 접근 차단 확인
   - [ ] HTTP 환경 보안 설정 확인

3. **성능 테스트**
   - [ ] 페이지 로딩 속도 확인
   - [ ] 이미지 최적화 확인
   - [ ] 브라우저 캐싱 동작 확인

4. **모니터링**
   - [ ] 에러 로그 모니터링
   - [ ] 트래픽 모니터링
   - [ ] 데이터베이스 성능 확인

---

**🎉 축하합니다! My Playground 사이트가 성공적으로 배포되었습니다.** 

## ⚠️ HTTP 환경 보안 주의사항

### 1. HTTP vs HTTPS 차이점
- **HTTP**: 데이터가 평문으로 전송되어 도청 가능
- **HTTPS**: SSL/TLS 암호화로 데이터 보호 (추가 비용 발생)

### 2. HTTP 환경에서 보안 대책
```php
// 1. 민감한 정보 처리 시 주의
// - 비밀번호는 서버 측에서 해시화 저장
// - 세션 ID 재생성으로 세션 하이재킹 방지
// - IP 주소 검증으로 무단 접근 차단

// 2. 추가 보안 조치
// - 로그인 시도 횟수 제한 (5회 실패 시 30분 잠금)
// - 관리자 페이지 접근 제한
// - 정기적인 비밀번호 변경 권장
```

### 3. 사용자 가이드라인
- 공용 Wi-Fi에서는 로그인 지양
- 비밀번호는 복잡하게 설정
- 로그인 후 반드시 로그아웃 실행
- 민감한 정보는 입력 자제

### 4. 향후 HTTPS 적용 방법
```text
1. 카페24 SSL 인증서 구매
2. 도메인에 SSL 적용
3. config.php에서 HTTPS로 변경
4. .htaccess에서 강제 리다이렉트 활성화
```

---

### 📞 지원 및 연락처

#### 카페24 고객센터
- 전화: 1588-5835
- 이메일: help@cafe24.com
- 온라인: https://help.cafe24.com

#### 개발 문의
- 프로젝트 관련 문의사항이 있으시면 GitHub Issues 또는 이메일로 연락해주세요.

---

## 🎯 배포 후 확인사항

1. **기본 기능 테스트**
   - [ ] 홈페이지 접속 확인
   - [ ] 로그인/로그아웃 기능
   - [ ] 각 모듈 정상 동작 확인

2. **보안 테스트**
   - [ ] 관리자 계정 비밀번호 변경
   - [ ] 민감한 파일 접근 차단 확인
   - [ ] HTTP 환경 보안 설정 확인

3. **성능 테스트**
   - [ ] 페이지 로딩 속도 확인
   - [ ] 이미지 최적화 확인
   - [ ] 브라우저 캐싱 동작 확인

4. **모니터링**
   - [ ] 에러 로그 모니터링
   - [ ] 트래픽 모니터링
   - [ ] 데이터베이스 성능 확인

---

**🎉 축하합니다! My Playground 사이트가 성공적으로 배포되었습니다.** 

**📍 배포 정보**
- **도메인**: http://gukho.net
- **FTP 주소**: huntkil.cafe24.com
- **데이터베이스**: huntkil (MariaDB)
- **환경**: HTTP (비SSL) 