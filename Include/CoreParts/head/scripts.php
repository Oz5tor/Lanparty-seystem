<!-- TinyMCE -->
<script type="text/javascript" src="JS/tinymce/tinymce.min.js"></script>
<script>
    // Public Editor for use at places like Forum, Profile text and so on.
  tinymce.init({
    selector: '#PublicTinyMCE',
    menubar: '',
    toolbar: 'undo redo | bold | blod italic | underline |',
    browser_spellcheck: true
  });
    // Administration Editor for use at places like news, pages, event and so on.
  tinymce.init({
    selector: '#AdminTinyMCE',
    menubar:'',
    plugins: [
    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
    'searchreplace wordcount visualblocks visualchars code fullscreen',
    'insertdatetime media nonbreaking save table contextmenu directionality',
    'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc'
  ],
  toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
  toolbar2: 'print preview media | forecolor backcolor emoticons | codesample | code',
    browser_spellcheck: true
  });
</script>
<!--[if IE]>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<!-- OneAll -->
<script type="text/javascript">
  /* Replace #your_subdomain# by the subdomain of a Site in your OneAll account */
  var oneall_subdomain = 'topper-tordk';
  /* The library is loaded asynchronously */
  var oa = document.createElement('script');
  oa.type = 'text/javascript'; oa.async = true;
  oa.src = '//' + oneall_subdomain + '.api.oneall.com/socialize/library.js';
  var s = document.getElementsByTagName('script')[0];
  s.parentNode.insertBefore(oa, s);
</script>

<?php if(@page == 'Gallery'){?> 
  <link rel="stylesheet" href="MagnificPopup/src/js/core.js">
  <link rel="stylesheet" href="MagnificPopup/src/js/ajax.js">
  <link rel="stylesheet" href="MagnificPopup/src/js/gallery.js">
  <link rel="stylesheet" href="MagnificPopup/src/js/image.js">
  <link rel="stylesheet" href="MagnificPopup/src/js/zoom.js">
  <link rel="stylesheet" href="MagnificPopup/src/js/retina.js">
<?php } ?>