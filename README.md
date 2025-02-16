# アプリケーション名
CoinPortfolio

# 概要
CoinPortfolioは暗号資産のポートフォリオ情報を管理するwebアプリケーションです.

サービスのURL
テスト用アカウント【メールアドレス：test@mail.com，パスワード：testtest】でログインしてご利用いただけます．

https://cypto-portfolio-a16d2c65ca0e.herokuapp.com/portfolio

# 開発した背景
近年、暗号資産の保有人口は急激な増加の一途を辿っています。多くの保有者は複数の場所(取引所、個人ウォレット、etc.)で暗号資産を保有しています。彼らはCoinmarketCapといったアプリで保有枚数などの保有情報を管理していますが、そうした既存アプリには複数の保有場所情報を載せる機能が欠けています。当アプリは、自分の暗号資産がどこにいくらあるかという情報を一元管理できるアプリです。

# 使用している主な技術
バックエンドフレームワーク Laravel

フロンドエンド言語 Javascript

バックエンド言語 PHP

データベース Mysql

インフラ Amazon aws

# ER図
下記のリンクがER図になります．
https://app.diagrams.net/?src=about#G1hBX53dqJFv4W7Axz0GQBLE2w0-5xzYDZ#%7B%22pageId%22%3A%22OExQpER_9SiVv6_RXGZ_%22%7D

# ディレクトリ構成
Laravelによる構成が基本となっている．

マイグレーションファイル \Crypto-Portfolio\src\database\migrations

モデル \Crypto-Portfolio\src\app\Models

コントローラー \Crypto-Portfolio\src\app\Http\Controllers

ビュー \Crypto-Portfolio\src\resources\views

ルーティング \Crypto-Portfolio\src\routes\web.php

# 今後の展望
