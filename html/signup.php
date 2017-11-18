<div class="row">
        <div class="col-xs-6 col-md-6 col-md-offset-3">
<p>Sign Up</p>
<?=$msg?>
<form method="post" action="signup">
	<div class="form-group">
        <label for="username">Username:</label>
        <input type="text" class="form-control" id="username" name="username" value="<?=Request::post('username')?>" required>
      </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" class="form-control" id="email" name="email" value="<?=Request::post('email')?>" required>
      </div>
     <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" class="form-control" id="password" name="password" value="<?=Request::post('password')?>" required>
      </div>
     <div class="form-group">
        <label for="address">Address:</label>
        <input type="text" class="form-control" id="address" name="address" value="<?=Request::post('address')?>" required>
      </div>
	<input type="submit" name="doSubmit" class="btn btn-success" value="Sign Up" />
    <a href="./login">Click here to login</a>
</form>
</div>
</div>