<?php
require '../controllers/config.php';
require '../controllers/database.php';
require_once '../controllers/lib.php';


session_start();

try{
    
    $title = "検索結果";
    
    if( ($login = auth()) == FALSE ) {
        header('Location: login.php');
        exit;
    }

?>
<?php require 'header.php' ?>
<?php require 'topbar.php' ?>

        <div align="center">
            <h2>検索結果</h2>
            <table>
                <tr>
                    <td>商品名</td>
                    <td>価格</td>
                    <td>作者</td>
                </tr>
<?php
    $category_id_search = "";
    $query_search = "";
    $sort_search = "";
    $item_id_search = "";

    if( isset($_GET['category_id'])) {
        $category_id_search = $_GET['category_id'];
    }
    if( isset($_GET['query'])) {
        $query_search = $_GET['query'];
    }

    if( isset($_GET['sort'])) {
        $sort_search = $_GET['sort'];
    }

    if( isset($_GET['item_id'])) {
        $item_id_search = $_GET['item_id'];
    }

    $sql1 = "";
    if($category_id_search != ""){
        $sql1 = " and category_id = :category_id ";
    } 

    $sql2 = "";
    if($query_search != ""){
        $query_search = '%'.$query_search.'%';
        $sql2 = " and item_name like :item_name ";
    } 

    $sql3 = "";
    if($item_id_search != ""){
        $sql3 = " and item_id = :item_id ";
    } 

    $sql_sort = "";
    if($sort_search != ""){
    $sql_sort = " order by " . $sort_search ;
    }
    
    $sql = "select item.*, author.author_name "
            . "from item left join author on item.author_id = author.author_id "
            . "where 1 = 1 " . $sql1 . $sql2 . $sql3 . $sql_sort;
 
    $stmt = $pdo -> prepare($sql);
    
    if($category_id_search != "" ){
        $stmt->bindValue(':category_id',  $category_id_search, PDO::PARAM_STR);
    }
    if($query_search != "" ){
        $stmt->bindParam(':item_name',  $query_search, PDO::PARAM_STR);
    }
    if($item_id_search != "" ){
        $stmt->bindValue(':item_id',  $item_id_search, PDO::PARAM_STR);
    }

    $stmt->execute();
    foreach($stmt as $loop) {
?>
                <tr>
                    <td><?php print($loop['item_name']) ?></td>
                    <td><?php print($loop['sale_price']) ?></td>
                    <td><?php print($loop['author_name']) ?></td>
                </tr>
<?php
}}
catch(Throwable $e) {
    error_log("[". date('Y-m-d H:i:s') . "]". __LINE__ . $e->getMessage());
}
?>
            </table>
        </div>

<?php require 'footer.php'; ?>