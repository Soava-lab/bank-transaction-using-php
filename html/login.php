<div class="row">
        <div class="col-xs-6 col-md-6 col-md-offset-3">
        <p><b>Login</b></p>
<span class="text-danger"><?=$msg?></span>
<form method="post">
    <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" class="form-control" id="username" name="username">
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" class="form-control" id="password" name="password">
    </div>
	<input type="submit" name="doLogin" value="Login" class="btn btn-success" />
    <a href="./signup">Click here to signup</a>
</form>
</div>
</div>

