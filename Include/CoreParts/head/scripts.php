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
    "advlist autolink lists link image charmap print preview anchor",
    "searchreplace visualblocks code fullscreen",
    "insertdatetime media table contextmenu paste imagetools"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
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
  var oneall_subdomain = 'hlpartyjoomla';
  /* The library is loaded asynchronously */
  var oa = document.createElement('script');
  oa.type = 'text/javascript'; oa.async = true;
  oa.src = '//' + oneall_subdomain + '.api.oneall.com/socialize/library.js';
  var s = document.getElementsByTagName('script')[0];
  s.parentNode.insertBefore(oa, s);
</script>
