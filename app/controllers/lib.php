<?php
function auth() {
    global $magic_code;
    if ( isset($_SESSION['login_id']) && isset($_SESSION['auth_code']) && md5 ($magic_code . $_SESSION['login_id']) == $_SESSION['auth_code']) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function make_category( $parent_id, $order, $sort, $value, $indent ) {
    global $pdo;
    $sql = "select * from category where parent_id = :parent_id
        and category_id >= :category_id order by $order $sort ";
    //prepareメソッドでSQLをセット
    $stmt = $pdo->prepare($sql);
    //bindValueメソッドでパラメータをセット
    $stmt->bindParam(':parent_id', $parent_id, PDO::PARAM_STR);
    $stmt->bindValue(':category_id', 0, PDO::PARAM_STR);
    //executeでクエリを実行
    $stmt->execute();
    
    foreach($stmt as $loop){
        if ( $loop['category_id'] == $value ){
            echo "<option value=${loop['category_id']} selected>$indent${loop['category_name']}\n ";
        } else {
            echo "<option value=${loop['category_id']}>$indent${loop['category_name']}\n ";
        }
        
        make_category($loop['category_id'], $order, $sort, $value, $indent . "&nbsp;");
        
    }
}

function calc_handling ( $subtotal, $payment_option) {
    switch ($payment_option) {
        case 0://クレジットカード
            if ( $subtotal >= 1500 )
                return 0;
            else {
                return 400;
            }
        case 1://銀行振込
            if ( $subtotal >= 1500 )
                return 0;
            else {
                return 400;
            }
        case 2://コンビニ
            if ( $subtotal >= 1500 )
                return 0;
            else {
                return 400;
            }
        case 3://代引き
            if ( $subtotal >= 1500 )
                return 0 + ceil($subtotal * 0.05);
            else {
                return 400 + ceil($subtotal * 0.05);
            }
    }
}
?>