<?php
require '../controllers/config.php';
require '../controllers/database.php';

session_start();
?>

<?php require 'header.php' ?>

<?php require 'topbar.php' ?>

<hr>
<h2 align="center">
    最近チェックした商品
</h2>

        <?php 
        if ( isset($_SESSION['checked_items']) && $_SESSION['checked_items'] == '' ) {
            echo "<table>";
            echo "<tr>";
            foreach( $_SESSION['checked_items'] as $value ) {
                $sql = "select * from item left join author on item.author_id = author.author_id
                    where item_id = :item_id ";
                //prepareメソッドでSQLをセット
                $stmt = $pdo->prepare($sql);
                //bindValueメソッドでパラメータをセット
                $stmt->bindParam(':item_id',$value);
                //executeでクエリを実行
                $stmt->execute();
                //結果を表示
                //データを一件だけ
                $result = $stmt->fetch();
        ?>
        <td>
            <a href="item.php?item_id=<?php $result['item_id'] ?>">
                <img src="<?php $result['image_url'] ?>" width="120" alt="<?php $result['item_name'] ?>" align="top" border="0">
            </a><br>
            <a href="item.php?item_id=<?php $result['item_id'] ?>">
                <?php $result['item_name'] ?><br>
                <?php $result['author_name'] ?>
            </a>
                                                                        
        </td>
        <?php    }
        echo "</tr>";
        echo "</table>";
        }
        ?>
        
<?php require 'footer.php'; ?>

