<?php
if(isset($_GET['new_member'])):
?>
<div class="alert alert-dismissible alert-success">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <strong>eklendiniz!</strong> çok güzel bi şekilde üye oldunuz. aşağıdan giriş yapabilirsiniz:</a>.
</div>
<?php endif; ?>

<div class="well">
  <form class="form-horizontal text-left" action='<?php echo Template::form_action_signin(); ?>' method="POST">
      <fieldset>
          <div id="legend">
              <legend class="">giriş</legend>
          </div>
          
          <div class="control-group">
              <label class="control-label"  for="member_name">kullanıcı adı</label>
              <div class="controls">
                  <input type="text" id="member_name" name="member_name" placeholder="" class="input-xlarge">
              </div>
          </div>
          
          <div class="control-group">
              <label class="control-label" for="member_passwd">şifre</label>
              <div class="controls">
                  <input type="password" id="member_passwd" name="member_passwd" placeholder="" class="input-xlarge">
              </div>
          </div>
          
          <div class="control-group">
              <div class="controls">
                  <button class="btn btn-success" name="signin">Login</button>
              </div>
          </div>
      </fieldset>
  </form>
</div>