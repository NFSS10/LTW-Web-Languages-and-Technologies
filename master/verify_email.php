<?php
	session_start();
	session_regenerate_id(true);

	ob_start();
	include ('templates/header.php');
	$buffer = ob_get_contents();
	ob_end_clean();

	$title = "ResReview | Email Verification";
	$buffer = preg_replace('/(<title>)(.*?)(<\/title>)/i', '$1' . $title . '$3', $buffer);
	echo $buffer;

	include_once('database/connection.php');
	include_once('database/queries.php');


	if(isset($_GET['email']) && isset($_GET['code']))
	{
		$email = $_GET['email'];
		$code = $_GET['code'];

		try {
			$row = getUnverifiedUserEmail($db, $email, $code);
		}
		catch (PDOException $e)
		{
			die($e->getMessage());
		}

		if($row)
		{
			$username = $row[0]['username'];
			$password = $row[0]['password'];
			$codeDB = $row[0]['code'];

			if($codeDB == $code) {
				try {
					insertUser($db, $username, $password, $email);
				} catch (PDOException $e) {
					die($e->getMessage());
				}
			}

			try
			{
				$inserted = getUserByUsernamePassword($db, $username, $password);
			}
			catch (PDOException $e)
			{
				die($e->getMessage());
			}

			if($inserted)
			{
				deleteUnverifiedUser($db, $email, $code);
				?>

				<div id="verify_success">
					<p id="green">User registered with success!</p>
					<p>Redirecting to login page in 3 seconds.</p>
				</div>

			<?php
				header("Refresh:3; url=login.php");
			}
			else
			{
				echo "Cannot register that user!";
			}
		}
		else
		{
			echo "There isnt such account to ativate!";
		}
	}

	include ('templates/footer.php');
?>