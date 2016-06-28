<div class="well">
    <form id="tab" action="<?php echo Template::form_action_signup(); ?>" method="POST">
        <fieldset>
            <div id="legend">
                <legend class="">kayıt ol</legend>
            </div>
            <div class="control-group">
                <label class="control-label" for="member_name">kullanıcı adı</label>
                <div class="controls">
                    <input id="member_name" name="member_name" type="text" value="" class="input-xlarge">
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label" for="member_passwd">şifre</label>
                <div class="controls">
                    <input id="member_passwd" name="member_passwd" type="password" value="" class="input-xlarge">
                </div>
            </div>
    
            <div class="control-group">
                <label class="control-label" for="member_mail">e-posta</label>
                <div class="controls">
                    <input id="member_mail" name="member_mail" type="text" value="" class="input-xlarge">
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label" for="member_fullname">tam isim</label>
                <div class="controls">
                    <input id="member_fullname" name="member_fullname" type="text" value="" class="input-xlarge">
                </div>
            </div>
            
            <div class="control-group">
                <div class="controls">
                    <button class="btn btn-success" name="signup">yolla</button>
                </div>
            </div>
        </fieldset>
    </form>
</div>