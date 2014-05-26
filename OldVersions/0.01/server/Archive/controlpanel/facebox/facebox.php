  <link href="facebox/src/facebox.css" media="screen" rel="stylesheet" type="text/css" />
  <link href="facebox/css/example.css" media="screen" rel="stylesheet" type="text/css" />
  <script src="facebox/lib/jquery.js" type="text/javascript"></script>
  <script src="facebox/src/facebox.js" type="text/javascript"></script>
  <script type="text/javascript">
    jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox({
        loadingImage : 'facebox/src/loading.gif',
        closeImage   : 'facebox/src/closelabel.png'
      })
    })
  </script>
