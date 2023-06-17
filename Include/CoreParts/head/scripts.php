<!-- TinyMCE -->
<script type="text/javascript" src="JS/tinymce/tinymce.min.js"></script>
<!--<script src="https://cdn.tiny.cloud/1/8dm4ddylkyjb6i9yba5v72nyrciup3v3r0mwn53ttollapk5/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>-->
<script>
    // Administration Editor for use at places like news, pages, event and so on.
  tinymce.init({
    selector: "#AdminTinyMCE",
    plugins: [
         "advlist autolink link image lists charmap print preview hr anchor pagebreak",
         "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
         "table contextmenu directionality emoticons paste textcolor responsivefilemanager code"
   ],
   toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
   toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
   image_advtab: true ,
   
   external_filemanager_path:"JS/filemanager/",
   filemanager_title:"Responsive Filemanager" ,
   external_plugins: { "filemanager" : "JS/filemanager/plugin.min.js"},
   image_class_list: [
    {title: 'img-responsive', value: 'img-responsive'}
   ]
 });


 tinymce.init({
    selector: "#PublicTinyMCE",
    height: "500",
    plugins: [
         "advlist autolink link image lists charmap print preview hr",
         "searchreplace wordcount visualblocks insertdatetime media nonbreaking",
         "table contextmenu directionality emoticons paste textcolor responsivefilemanager code"
   ],
   toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
   toolbar2: "link unlink anchor | image media | forecolor backcolor",
   image_class_list: [
    {title: 'img-responsive', value: 'img-responsive'}
   ]
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

<?php if($page == 'Gallery'){?> 
  <link rel="stylesheet" href="MagnificPopup/src/js/core.js">
  <link rel="stylesheet" href="MagnificPopup/src/js/ajax.js">
  <link rel="stylesheet" href="MagnificPopup/src/js/gallery.js">
  <link rel="stylesheet" href="MagnificPopup/src/js/image.js">
  <link rel="stylesheet" href="MagnificPopup/src/js/zoom.js">
  <link rel="stylesheet" href="MagnificPopup/src/js/retina.js">
<?php } ?>