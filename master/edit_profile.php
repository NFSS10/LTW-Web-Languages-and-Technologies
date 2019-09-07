<?php
session_start();
session_regenerate_id(true);

ob_start();
include ('templates/header.php');
$buffer = ob_get_contents();
ob_end_clean();

$title = "ResReview | Edit Profile";
$buffer = preg_replace('/(<title>)(.*?)(<\/title>)/i', '$1' . $title . '$3', $buffer);
echo $buffer;

if(isset($_SESSION['login_user']))
{
	include_once('database/connection.php');
	include_once('database/queries.php');
	require('lib/password.php');

	$user = $_SESSION['login_user'];

	try {
		$user_info = getCurrentUserInfo($db, $user);
	}
	catch (PDOException $e)
	{
		die($e->getMessage());
	}

	if($_SERVER["REQUEST_METHOD"] == "POST") {

		$olderpasswordGiven = $_POST['old_password'];

		if(isset($_POST['avatar_change_sub']))
		{
			$target_dir = "resources/avatars";
			if(!file_exists($target_dir))
				mkdir($target_dir, 0777, true);

			$target_file = "resources/avatars/" . basename($_FILES['input_ava']['name']);

			$maxDimW = 150;
			$maxDimH = 150;

			list($width, $height, $type, $attr) = getimagesize( $_FILES["input_ava"]['tmp_name'] );

			if($target_file != $target_dir && $_FILES["input_ava"]["size"] < 1000000 && ($width > $maxDimW || $height > $maxDimH))
			{
				$target_filename = $_FILES["input_ava"]['tmp_name'];
				$size = getimagesize( $target_filename );
				$ratio = $size[0]/$size[1];

				if( $ratio > 1) {
					$width = $maxDimW;
					$height = $maxDimH/$ratio;
				} else {
					$width = $maxDimW*$ratio;
					$height = $maxDimH;
				}

				$src = imagecreatefromstring(file_get_contents($target_filename));
				$dst = imagecreatetruecolor( $width, $height );
				imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);

				imagejpeg($dst, $target_filename);

				$target_f_file = "resources/avatars/$user.jpg";

				if(file_exists($target_f_file))
					unlink($target_f_file);

				if(move_uploaded_file($_FILES['input_ava']['tmp_name'], $target_f_file))
						updateUserPhoto($db, $user, $target_f_file);

			}
		}
		else{

			//if user didnt change, set default values
			if ($_POST['new_password'] == "")
				$new_password = $user_info['password'];
			else
				$new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

			if($_POST['email'] == "")
				$email = $user_info['email'];
			else
				$email = strip_tags($_POST['email']);

			$city = strip_tags($_POST['city']);
			$address = strip_tags($_POST['address']);
			$phone = strip_tags($_POST['phone']);
			$name = strip_tags($_POST['name']);

			try {
				updateUserInfo($db, $user, $new_password, $email, $city, $address, $phone, $name);
			} catch (PDOException $e) {
				die($e->getMessage());
			}
		}
		header("Location:edit_profile.php");
	}
	?>

	<script type="text/javascript" src="scripts/edit_profile.js"> </script>

	<div class="manage_all">
		<?php include('templates/left_menu.php'); ?>

		<div class="c_user_img">
			<img id="avatar" src= <?= $user_info['photo'] ?> alt="avatar">

			<form method="POST" action="" enctype="multipart/form-data">
				<div class="change_avatar">
					<label for='input_ava'> Upload &#187;</label>
					<input type="file" id="input_ava" name="input_ava" accept="image/*">
					<input type="submit" value="Change Avatar" name="avatar_change_sub">
				</div>
			</form>

		</div>

		<div class="user_form">
			<form method="POST" action="" id="manage_acc">
				<label>Old Password: </label><br>
				<input type="password" name="old_password" id="old_password"><span id="oldpasswordmessage"></span><br>
				<label>New Password: </label><br>
				<input type="password" name="new_password" id="new_password"><span id="passwordmessage"></span><br>
				<label>Confirm Password:</label><br>
				<input type="password" name="new_password2" id="new_password2"><span id="passwordmessage2"></span><br>
				<label>Name:</label><br>
				<input type="text" name="name" id="name" value= <?=$user_info['name']?>><br>
				<label>Email:</label><br>
				<input type="e-mail" name="email" id="email" value= <?=$user_info['email']?>><span id="emailmessage"></span><br>
				<label>City:</label><br>
				<input type="text" name="city" id="city" value= <?=$user_info['city']?>><br>
				<label>Address:</label><br>
				<input type="text" name="address" id="adress" value= <?=$user_info['address']?>><br>
				<label>Phone Number:</label><br>
				<input type="tel" name="phone" id="phone_number" value= <?=$user_info['phone']?>><span id="phonemessage"></span> <br><br>
				<input class="sub" type="submit" value="Save changes" id="submit" name="submit">
			</form>
		</div>

	</div>
	<?php
}
else
{
	include ('templates/no_login.php');
}

include ('templates/footer.php');
?>
