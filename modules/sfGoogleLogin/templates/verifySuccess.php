<?php use_helper('I18N') ?>
<?php if ($success) : ?>
  <script type="text/javascript">
    /* <![CDATA[ */

    window.close();

    /* ]]> */
  </script>
  <h3><?php echo __('You are now logged in.') ?></h3>
<?php else : ?>
    <h3><?php echo __('Login Error!') ?></h3>
<?php endif; ?>

    <p><?php
    echo __('Close this window to return to your application.')
?></p>
