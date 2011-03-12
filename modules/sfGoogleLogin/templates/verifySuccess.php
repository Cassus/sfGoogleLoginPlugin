<?php use_helper('I18N') ?>
<?php if ($success) : ?>
  <script type="text/javascript">
    /* <![CDATA[ */

    //window.close();

    /* ]]> */
  </script>
  <h3><?php echo __('You are now logged in.') ?></h3>
<?php else : ?>
    <h3><?php echo __('Login Error!') ?></h3>
<?php endif; ?>

    <p><?php
    echo __('Close this window to continue or go to your <a href=":url">last page</a>.', array(
        ':url' => url_for($sf_user->getAttribute('sfGoogleLogin_returnTo'))
    ))
?></p>
