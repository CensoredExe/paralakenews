<?php
ob_start();
session_start();

function logoutbutton() {
	echo "<form action='' method='get'><button name='logout' type='submit'>Logout</button></form>"; //logout button
}
date_default_timezone_set("Europe/London");

function loginbutton($buttonstyle = "square") {
	$button['rectangle'] = "01";
	$button['square'] = "02";
	$button = "<a href='?login'><img src='https://steamcommunity-a.akamaihd.net/public/images/signinthroughsteam/sits_".$button[$buttonstyle].".png'></a>";
	
	echo $button;
}

if (isset($_GET['login'])){
	require 'openid.php';
	try {
		require 'SteamConfig.php';
		$openid = new LightOpenID($steamauth['domainname']);
		
		if(!$openid->mode) {
			$openid->identity = 'https://steamcommunity.com/openid';
			header('Location: ' . $openid->authUrl());
		} elseif ($openid->mode == 'cancel') {
			echo 'User has canceled authentication!';
		} else {
			if($openid->validate()) { 
				
				$id = $openid->identity;
				$ptn = "/^https?:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/";
				preg_match($ptn, $id, $matches);
				
				$_SESSION['steamid'] = $matches[1];
				$steam = $_SESSION['steamid'];
				// Check if account exists
				$sql = "SELECT * FROM `users` WHERE `user_steam`='$steam'";
				$result = mysqli_query($conn, $sql);
				if(mysqli_num_rows($result) > 0){
					// Account exists
					
					while($row = mysqli_fetch_assoc($result)){
						$_SESSION['user_id'] = $row['user_id'];
						$_SESSION['user_name'] = $row['user_name'];
						$_SESSION['user_bio'] = $row['user_bio'];
                        $_SESSION['user_role'] = $row['user_role'];
						$_SESSION['user_steam'] = $row['user_steam'];
						$_SESSION['user_avatar'] = $row['user_avatar'];
						$name = "user";
						$value = $_SESSION['user_steam'];
						$string = generateRandomString(25);
						$sql = "INSERT INTO `cookies` (`steamid`, `value`) VALUES ('$value', '$string')";
						mysqli_query($conn, $sql);
						setcookie($name,$string, time() + (86400 * 30), "/");
					}
				}else {
					
					$url = file_get_contents("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=".$steamauth['apikey']."&steamids=".$_SESSION['steamid']); 
					$content = json_decode($url, true);
					// Create account
					$dos = date("H:i:s d/m/y");
                    $role = "user";
					$balance = 0.0;
					$userpfp = $content['response']['players'][0]['avatarfull'];
					$name =  $content['response']['players'][0]['personaname'];
					$bio = "This user has not set a custom bio....";
					$sql = "INSERT INTO `users` (`user_name`,  `user_role`, `user_bio`, `user_dos`, `user_steam`, `user_avatar`) 
					VALUES ('$name', '$role', '$bio' ,'$dos', '$steam', '$userpfp')";
					
					mysqli_query($conn, $sql);
					$sql = "SELECT * FROM `users` WHERE `user_steam`='$steam'";
					$result = mysqli_query($conn, $sql);
					
					while($row = mysqli_fetch_assoc($result)){
						$_SESSION['user_id'] = $row['user_id'];
						$_SESSION['user_bio'] = $row['user_bio'];
                        $_SESSION['user_name'] = $row['user_name'];
                        $_SESSION['user_code'] = $row['user_code'];
                        $_SESSION['user_role'] = $row['user_role'];
                        $_SESSION['user_steam'] = $row['user_steam'];
						$_SESSION['user_avatar'] = $row['user_avatar'];
						$name = "user";
						$value = $_SESSION['user_steam'];
						$string = generateRandomString(25);
						$sql = "INSERT INTO `cookies` (`steamid`, `value`) VALUES ('$value', '$string')";
						mysqli_query($conn, $sql);
						setcookie($name,$string, time() + (86400 * 30), "/");
					}
				}
				if (!headers_sent()) {
					header('Location: '.$steamauth['loginpage']);
					
					exit;
				} else {
					?>
					<script type="text/javascript">
						window.location.href="<?=$steamauth['loginpage']?>";
					</script>
					<noscript>
						<meta http-equiv="refresh" content="0;url=<?=$steamauth['loginpage']?>" />
					</noscript>
					<?php
					exit;
				}
			} else {
				echo "User is not logged in.\n";
			}
		}
	} catch(ErrorException $e) {
		echo $e->getMessage();
	}
}

if (isset($_GET['logout'])){
	require 'SteamConfig.php';
	session_unset();
	session_destroy();
	setcookie("user", "", time() - 3600, "/");
	header('Location: '.$steamauth['logoutpage']);
	exit;
}

if (isset($_GET['update'])){
	unset($_SESSION['steam_uptodate']);
	require 'userInfo.php';
	header('Location: '.$_SERVER['PHP_SELF']);
	exit;
}

// Version 4.0

?>
