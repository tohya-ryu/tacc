<div>
  <b><?php echo strtoupper(Framework::locale()->auth_header_login()); ?></b>
</div>
<div>
  <form id="auth-login" class="framework-form" data-method="post">
    <div class="framework-validation-notice"></div>
    <div>
      <label for="auth-email">
        <?php echo Framework::locale()->auth_inp_name_email(); ?>
      </label>
      <input type="email" name="auth-email" id="auth-email"/>
      <div id="validation-errors-auth-email"></div>
    </div>
    <div>
      <label for="auth-password">
        <?php echo Framework::locale()->auth_inp_name_password(); ?>
      </label>
      <input type="password" name="auth-password" id="auth-password"/>
      <div id="validation-errors-auth-password"></div>
    </div>
    <div>
      <input type="checkbox" name="auth-remember" id="auth-remember" />
      <label for="auth-remember">
        <?php echo Framework::locale()->auth_remember_login(); ?>
      </label>
    </div>
    <input type="hidden" id="csrf-token" name="csrf-token"
      value="<?php echo $this->view->csrf_token ?>"/>
    <div>
      <button type="button" data-uri="<?php $this->base_uri("auth/login"); ?>" 
        class="framework-form-submit">
        <?php echo Framework::locale()->auth_button_login(); ?>
      </button>
    </div>  
  </form>    
  <div>
    <a href="<?php $this->base_uri('auth/signup'); ?>">
      <?php echo Framework::locale()->auth_link_signup(); ?>
    </a>
    <br>
    <a href="
      <?php $this->base_uri('auth/pwreset/apply'); ?>">
      <?php echo Framework::locale()->auth_link_pwreset(); ?>
    </a>
  </div>
</div>
