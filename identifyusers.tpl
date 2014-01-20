 <!-- KISSmetrics Identify Users -->
{if !empty($cookie->email)}
  <script type="text/javascript">  _kmq.push(['identify', "{$cookie->email}"]);</script>
{/if}
