# アプリケーション名
商品管理システム「mogitate」

## 環境構築
1.  **リポジトリのクローン**
    ```bash
    git clone https://github.com/your-repository/mogitate-test.git
    cd mogitate-test
    ```

2.  **Dockerコンテナのビルドと起動**
    ```bash
    docker-compose up -d --build
    ```

3.  **Laravel依存パッケージのインストール**
    ```bash
    docker-compose exec php composer install
    ```

4.  **.envファイルの作成**
    `.env.example`をコピーして`.env`を作成します。
    ```bash
    cp src/.env.example src/.env
    ```
    
    **注意**: `.env`ファイルのDB設定は以下の通りです：
    - `DB_HOST=mysql` (Dockerサービス名)
    - `DB_DATABASE=laravel_db`
    - `DB_USERNAME=laravel_user`
    - `DB_PASSWORD=laravel_pass`

5.  **アプリケーションキーの生成**
    ```bash
    docker-compose exec php php /var/www/artisan key:generate
    ```

6.  **データベースのマイグレーションとシーディング**
    ```bash
    docker-compose exec php php /var/www/artisan migrate:fresh --seed
    ```
    
7. **ストレージリンクの作成**
   ```bash
   docker-compose exec php php /var/www/artisan storage:link
   ```

## 開発時の注意事項

### データベースの初期化
開発時にデータベースを完全にリセットしたい場合：

```bash
# データを削除してAUTO_INCREMENTをリセット
docker-compose exec mysql mysql -u laravel_user -plaravel_pass -e "USE laravel_db; DELETE FROM seasons; ALTER TABLE seasons AUTO_INCREMENT = 1;"

# シーダーを再実行
docker-compose exec php bash -c "php artisan db:seed --class='Database\\Seeders\\SeasonSeeder'"
```

**⚠️ 注意**: このコマンドは本番環境や他の開発者のデータベースには実行しないでください。

### 外部キー制約のあるテーブルの初期化
`product_season`テーブルなど、外部キー制約があるテーブルを初期化する場合：

```bash
# 関連テーブルも一緒に削除
docker-compose exec mysql mysql -u laravel_user -plaravel_pass -e "USE laravel_db; DELETE FROM product_season; DELETE FROM seasons; ALTER TABLE seasons AUTO_INCREMENT = 1;"
```

### 新しいクラスを追加した場合
新しいモデルやシーダーを追加した場合は、オートローダーを再生成してください：

```bash
docker-compose exec php composer dump-autoload
```

### Dockerボリュームの管理
データベースを完全にリセットしたい場合（開発時のみ）：

```bash
# コンテナとボリュームを削除
docker-compose down -v

# 再起動
docker-compose up -d
```

## 使用技術（実行環境）
-   **PHP**: 7.4.9
-   **Laravel Framework**: 8.83.29
-   **MySQL**: 8.0.26
-   **Nginx**: 1.21.1
-   **Docker**

## ER図
```mermaid
erDiagram
    products {
        bigint id PK "商品ID"
        varchar name "商品名"
        int price "商品料金"
        varchar image "商品画像"
        text description "商品説明"
        timestamp created_at
        timestamp updated_at
    }

    seasons {
        bigint id PK "季節ID"
        varchar name "季節名"
        timestamp created_at
        timestamp updated_at
    }

    product_season {
        bigint id PK "ID"
        bigint product_id FK "商品ID"
        bigint season_id FK "季節ID"
        timestamp created_at
        timestamp updated_at
    }

    products ||--o{ product_season : "has"
    seasons ||--o{ product_season : "has"
```

## URL
-   **開発環境**: [http://localhost/](http://localhost/)
-   **phpMyAdmin**: [http://localhost:8080/](http://localhost:8080/)

## 開発環境

- PHP 7.4.9
- Laravel 8.x
- MySQL 8.0.26
- Nginx 1.21.1

## 機能一覧

- 商品一覧表示
- 商品詳細表示
- 商品登録
- 商品更新
- 商品削除
- 商品検索
- 商品並び替え 