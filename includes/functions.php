<?php
    date_default_timezone_set("Europe/London");
    include_once "connection.php";

    function getUser($id){
        global $conn;
        $sql = "SELECT * FROM `users` WHERE `user_id`='$id'";
        $result = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_assoc($result)){
            return $row['user_name'];
        }
        return "User not found.";
    }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function getIp(){
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    

    function addLog($content, $user){
        global $conn;
        $dos = date("H:i:s d/m/y");
        $sql = "INSERT INTO `logs` (`log_content`, `log_user`, `log_date`) VALUES ('$content', '$user', '$dos')";
        mysqli_query($conn, $sql);
        
    }
   
    function getCat($id){
        global $conn;
        $sql = "SELECT * FROM `categories` WHERE `id`='$id'";
        $result = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_assoc($result)){
            return $row['title'];
        }
    }
    function getCatColor($id){
        global $conn;
        $sql = "SELECT * FROM `categories` WHERE `id`='$id'";
        $result = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_assoc($result)){
            return $row['color'];
        }
    }
    function increaseOrders($id, $amount){
        global $conn;
        $sql = "SELECT * FROM `products` WHERE `product_id`='$id'";
        $result = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_assoc($result)){
            $current = $row['product_orders'];
        }
        $new = $current + $amount;
        $sql = "UPDATE `products` SET `product_orders`='$new' WHERE `product_id`='$id'";
        mysqli_query($conn, $sql);
    }
    function increaseUserOrders($id, $amount){
        global $conn;
        $sql = "SELECT * FROM `users` WHERE `user_id`='$id'";
        $result = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_assoc($result)){
            $current = $row['user_orders'];
        }
        $new = $current + $amount;
        $sql = "UPDATE `users` SET `user_orders`='$new' WHERE `user_id`='$id'";
        mysqli_query($conn, $sql);
    }
    function getProduct($id){
        global $conn;
        $sql = "SELECT * FROM `products` WHERE `product_id`='$id'";
        $result = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_assoc($result)){
            return $row['product_title'];
        }
        return "Error, user deleted.";
    }
    function increaseUserOrdersCompleted($id, $amount){
        global $conn;
        $sql = "SELECT * FROM `users` WHERE `user_id`='$id'";
        $result = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_assoc($result)){
            $current = $row['user_completedorders'];
        }
        $new = $current + $amount;
        $sql = "UPDATE `users` SET `user_completedorders`='$new' WHERE `user_id`='$id'";
        mysqli_query($conn, $sql);
    }
    function getSteam($id){
        global $conn;
        $sql = "SELECT * FROM `users` WHERE `user_id`='$id'";
        $result = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_assoc($result)){
            return $row['user_steam'];
        }
        return "Error, user deleted.";
    }
    function login($steam){
        global $conn;

        $sql = "SELECT * FROM `cookies` WHERE `value`='$steam'";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result)<1){
            // Someone is trying to hack an account
            
            
            setcookie("user", "", time() - 3600, "/");
            
            
        }else {
            while($row = mysqli_fetch_assoc($result)){
                $steam = $row['steamid'];
            }
    
            $sql = "SELECT * FROM `users` WHERE `user_steam`='$steam'";
            $result = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_assoc($result)){
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['user_name'] = $row['user_name'];
                $_SESSION['user_role'] = $row['user_role'];
                $_SESSION['user_steam'] = $row['user_steam'];
                $_SESSION['user_avatar'] = $row['user_avatar'];
                
            }
            $name = "user";
			$value = $_SESSION['user_steam'];
			$string = generateRandomString(25);
			$sql = "INSERT INTO `cookies` (`steamid`, `value`) VALUES ('$value', '$string')";
			mysqli_query($conn, $sql);
			setcookie($name,$string, time() + (86400 * 30), "/");
        }
    }
    function checkLogin(){
        global $conn;
        if(isset($_SESSION['user_id'])){
            return;
        }else {
            // not logged in
            if(isset($_COOKIE['user'])){
                // Logged in before
                login($_COOKIE['user']);
                echo "<script>window.location=window.location</script>";
            }else {
                return;
            }
        }
    }
    function urlToID($url){
        global $conn;
        $sql = "SELECT * FROM `articles` WHERE `url`='$url'";
        $result = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_assoc($result)){
            return $row['id'];
        }
    }
    function addView($article){
        global $conn;
        $ip = getIp();
        $sql = "SELECT * FROM `views` WHERE `page`='$article' AND `ip`='$ip'";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result)>0){
            return;
        }else {
            $sql = "INSERT INTO `views` (`page`, `ip`) VALUES ('$article', '$ip')";
            mysqli_query($conn, $sql);
            
        }
    }
    function getViews($article){
        global $conn;
        $sql = "SELECT * FROM `views` WHERE `page`='$article'";
        $result=mysqli_query($conn, $sql);
        return mysqli_num_rows($result);
    }
    function getUserViews($id){
        global $conn;
        $total = 0;
        $sql = "SELECT * FROM `articles` WHERE `author`='$id'";
        $result=mysqli_query($conn, $sql);
        while($row = mysqli_fetch_assoc($result)){
            $aid = $row['id'];
            $total = $total + getViews($aid);
        }
        return $total;
    }
?>