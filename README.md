# 業務日報システムをウェブで作る目的を整理します。
1. 業務日報システムで何をしたいか。
　日記形式で記述していたものを、項目別に分けデータ保存に、後から利用活用できるようにすることを目的とします。

2. 誰が使うのか。他の人も使えるのか。
    - 当面は自分自身のみで使用します。
    - ログイン機能が必要なのでは?　→複数ユーザー使用も見据え設計していきます。

3. どのように便利に効率化されるか。
    - いつでも、どこでも利用できるようにします。

4. 今までと何が違うのか。
    - 仕事の場所と時間の制約に対応するようにします。
    - 自分の自由な時間を増やすようにします。
    　→自己管理、時間管理をしっかりすることが前提です。

5. ここだけはシステム開発で
    - データ入力、検索、出力はシステム上でおこないたいです。

6. 技術課題
    - PHPのプログラミングに慣れたいです。
    - 多言語をいろいろ勉強していきたいです。
 ---

#業務日報システムに必要な機能を整理します。 
*どのような機能が必要か、今時点で考えられる範囲で書き出します。
*最初は基本機能を整理し、後にログイン機能やセキュリティー面の機能を追加していくこととします。
*作っていくうちに、内容はたぶん変わっていきます。
 

1. システム機能を整理する
    - 日報データ入力と登録
    - 日報データの検索と閲覧
    - 顧客マスタデータの登録、修正、削除
    - ユーザーマスタデータの登録、修正、削除
    - 日報データの出力（データファイル、プリンター）
    - 顧客データの個別表示、一覧表示
    - ユーザーログイン機能
    - メニュー機能
    - データバックアップ、リストア

2. 画面の遷移を整理する
　　おおまかな遷移図を整理し、修正追加は随時おこなっていく。　

　1）ログイン画面　
　2）メインメニュー画面　　
　3-1）日報検索、閲覧画面
　　3-1-2）日報新規登録、修正登録画面
　3-2）顧客マスタ新規登録、修正登録画面
　3-3）運用管理　　　　　　　　　  
　　3–3-1）ユーザーマスタ新規登録、修正登録、権限設定画面
　　3–3-2）バックアップ、リストア画面
---   
