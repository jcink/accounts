<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Account System</title>
  <meta name="description" content="Account System">
  <meta name="author" content="John Cuppi">
	<style>
	#content {
		border: 2px solid #a1a1a1;
		padding: 10px 40px; 
		background: #dddddd;
		width: 500px;
		margin-bottom: 5px;
	}
	
	.center { text-align: center; }
	
	label {  
		float: left;  
		width: 10em;  
		margin-right: 1em;  
		text-align: right; 
		font-weight: bold;
	}

	input[type=submit] {
		margin-top: 5px;
		padding:5px 15px; background:#ccc; 
		border:0 none;
		cursor:pointer;
		-webkit-border-radius: 5px;
		border-radius: 5px; 
	}
		
	</style>
</head>
<body>

<?php
print $this->logged_in_as();
?>
<br />


<div id='content'>
	<form action='example.php?act=login' method='POST'>
	<label for="name">Name:</label>
	<input type='text' name='name' />
	<br />
	<label for="password">Password:</label>
	<input type='password' name='password' /> <br />
	<div align='center'>
		<input type='submit' name='login' value='Login' />
	</div>
</form>
</div>

<br />

<div id='content'>
	<form action='example.php?act=register' method='POST'>
	<label for="name">Name:</label> 
	<input type='text' name='name' />
	
	<br />
		
	<label for="password">Password:</label> 
	<input type='password' name='password' /> 

	<br />
	
	<label for="email">Email:</label> 
	<input type='text' name='email' />
	
	<br />
	
	<div align='center'>
		<input type='submit' name='register' value='Register' />
	</div>
	</form>
</div>

<body>

</body>
</html>