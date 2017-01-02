<?php if($_SESSION['Admin'] != 1) { header("Location: index.php"); } /* Fuck off... */ ?>
<div class="row">
    <div class="col-lg-2 hlpf_newsborder">
        <ul>
            <li><a href="" target="admin-frame">This</a></li>
            <li><a href="" target="admin-frame">Needs</a></li>
            <li><a href="" target="admin-frame">Some</a></li>
            <li><a href="" target="admin-frame">Styling</a></li>
        </ul>
    </div>
    <dir class="col-lg-1"><!-- Spaaaaace! --></dir>
    <div class="col-lg-9 hlpf_newsborder">
        <iframe src="" name="admin-frame" width=""></iframe>
    </div>
</div>
