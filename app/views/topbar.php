<?php
require '../controllers/config.php';
require '../controllers/database.php';
require_once '../controllers/lib.php';

?>

<div align="center">
    <table width="80%">
        <tr>
            <td>
                <a href='<?php print($site_top_url) ?>'><?php print($site_name); ?></a>
            </td>
            <td align='right'>
<?php if ( auth() ) { ?>
                こんにちは<?php print($_SESSION['name_kanji']); ?>さん
                [<a href='cart.php'>カート</a>]
                [<a href='account.php'>アカウント</a>]
                [<a href='logout.php'>ログアウト</a>]
<?php } else { ?>
                [<a href='login.php'>ログイン</a>]
                [<a href='register1.php'>会員登録</a>]
<?php } ?>
                [<a href='help.php'>ヘルプ</a>]
            </td>
        </tr>
        <form action="result.php" method="get">
            <tr align='center'>
                <td colspan="2">
                    |
<?php
try{
    $sql = "select * from category where parent_id = :parent_id order by category_name "; 
    //prepareメソッドでSQLをセット
    $stmt = $pdo->prepare($sql);
    //bindValueメソッドでパラメータをセット
    $stmt->bindValue(':parent_id', 0, PDO::PARAM_STR);
    //executeでクエリを実行
    $stmt->execute();
    
    foreach($stmt as $loop){
        echo "<a href=result.php?category_id=${loop['category_id']}>${loop['category_name']}</a> | ";
    }
}catch(Throwable $e) {
    error_log("[". date('Y-m-d H:i:s') . "]". __LINE__ . $e->getMessage());
}
?>
                    <select name='category_id'>
<?php 
if ( !isset($_GET['category_id'])) { $_GET['category_id'] = ''; } 
if ( !isset($_GET['query'])) { $_GET['query'] = ''; }
if ( !isset($_GET['sort'])) { $_GET['sort'] = ''; }

?>
                        <option value="" <?php if ( $_GET['category_id'] == '') { echo 'selected';} ?>>指定しない</option>
<?php make_category ( 0, 'type, category_name', 'desc', $_GET['category_id'], '') ?>
                    </select>

                    <input type="text" name='query' value="<?php print($_GET['query']); ?>" size="32">
                    並び替え：
                    <select name='sort'>
                        <option value='item_name asc' <?php if ( $_GET['sort'] == 'item_name asc') { echo 'selected'; } ?>>商品名の昇順</option>
                        <option value='item_name desc' <?php if ( $_GET['sort'] == 'item_name desc') { echo 'selected'; } ?>>商品名の降順</option>
                        <option value='sale_price asc' <?php if ( $_GET['sort'] == 'sale_price asc') { echo 'selected'; } ?>>価格の昇順</option>
                        <option value='sale_price desc' <?php if ( $_GET['sort'] == 'sale_price desc') { echo 'selected'; } ?>>価格の降順</option>
                    </select>
                    <input type="submit" value="検索">
                </td>
            </tr>
        </form>
    </table>
</div>