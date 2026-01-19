# CRM Application（Laravel + Docker）

## 概要
Laravel と Docker を用いて構築した、シンプルな CRM（顧客管理）アプリケーションです。  
顧客情報の管理に加え、権限管理・検索・CSV出力・監査ログ機能を実装しています。

業務利用を想定し、再現性・保守性・運用面を重視して設計しています。

---

## 主な機能

### 認証・権限
- Laravel Breeze による認証
- ユーザー権限：admin / staff  
  - admin：全顧客を閲覧・編集可能  
  - staff：自身が担当する顧客のみ編集可能（Policy制御）

### Customers（顧客管理）
- 一覧表示
- キーワード検索（name / company / email / phone）
- ステータス絞り込み
- ページネーション
- 編集（Edit）
- CSVエクスポート（検索条件を反映）

### 監査ログ（Activity Logs）
- Customer の 作成 / 更新 / 削除 を自動記録
- 記録内容：
  - 操作者（user_id）
  - 操作種別（created / updated / deleted）
  - 対象データ（Customer）
  - 変更前 / 変更後（before / after）
  - IPアドレス
- 監査ログ一覧画面にて検索・確認可能

※ customers テーブルは 最新状態、activity_logs テーブルは 履歴を保持します。

---

## 技術スタック
- PHP / Laravel
- Laravel Breeze（認証）
- MySQL
- Docker / Docker Compose
- Blade / Tailwind CSS

---

## 環境構成（Docker）
- app（Laravel / PHP）
- db（MySQL）
- node（Vite / フロントエンドビルド）
- phpMyAdmin（DB確認用）

---

## セットアップ手順

```bash
git clone https://github.com/KEN01-dev/laravel-crm.git
cd crm

cp .env.example .env

docker compose up -d --build

docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed

docker compose exec node npm install
docker compose exec node npm run build
テストユーザー
権限	Email	Password
admin	admin@example.com	password
staff	staff@example.com	password

※ シーダーで作成しています。

画面一覧
/login ログイン

/customers 顧客一覧

/customers/{id}/edit 顧客編集

/activity-logs 監査ログ一覧

設計ポイント
Policy による権限制御

Observer による監査ログ自動記録

検索条件を維持した CSV 出力

Docker によるローカル再現性の担保

UI は Breeze 標準を活かし、過度な装飾は行っていません

備考
本アプリケーションは学習・案件提出用途として作成しています。