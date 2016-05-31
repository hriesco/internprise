<?php
  require_once __DIR__.'/includes/config.php';
?>

<!DOCTYPE html>
<html>
<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/estilo_index.css" />
	<title>Internprise</title>
</head>

<body>
	<main>
		<section id="left"></section>
		<article id="main_login">
			<header id="login">
				<h1>Login</h1>
				<?php $formLogin = new \es\ucm\aw\internprise\FormularioLogin(); $formLogin->gestiona(); ?>
			</header>
		</article>
		<section id="right"></section>
	</main>
	</section>
	<nav>
		<ul>
			<li><a href="index.php">Home</a></li>
			<li><a href="about.php">About us</a></li>
			<li><a href="login.php">Login</a></li>
			<li><a href="register.php">Register</a></li>
			<li><a href="contact.php">Contact</a></li>
			<li class="slide"></li>
		</ul>
	</nav>
</body>
</html>