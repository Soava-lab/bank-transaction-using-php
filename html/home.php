<div class="row">
        <div class="col-xs-6 col-md-6 col-md-offset-3"><br><br>
<p align="center">
<b>Welcome to ABC Bank</b><br><br>
<?php if(Session::get('is_login')){ ?>
<a href="./dashboard">Go to account</a>
<?php }else{?>
<a href="./login" class="btn btn-success">Login</a> <a href="./signup" class="btn btn-success">SignUp</a>
<?php }?>
</p>
</div>
</div>