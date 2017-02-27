<!-- ===================================== -->
<div id="oa_social_login_container"></div>
<script type="text/javascript">
  /* Replace #your_callback_uri# with the url to your own callback script */
  var your_callback_script = 'http://<?php echo $ROOTURL; ?>Include/oneall_hlpf/oneall_callback_handler.php';
  /* Embeds the buttons into the container oa_social_login_container */
  var _oneall = _oneall || [];
  _oneall.push(['social_login', 'set_providers', ['facebook', 'Google', 'Battlenet', 'Steam', 'Twitch']]);
  _oneall.push(['social_login', 'set_callback_uri', your_callback_script]);
  _oneall.push(['social_login', 'do_render_ui', 'oa_social_login_container']);
</script>
<!-- ===================================== -->