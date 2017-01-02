<?php if($_SESSION['Admin'] != 1) { header("Location: index.php"); } /* Fuck off... */ ?>
<div class="row">
    <div class="col-lg-2 col-sm-12 hlpf_adminmenu">
        <ul>
            <li><a href="Include/Admin/users.php">Brugere</a></li>
            <li><a href="Include/Admin/nyheder.php">Nyheder</a></li>
            <li><a href="Include/Admin/Page.php">Pages</a></li>
            <li><a href="Include/Admin/nyheder.php">Styling</a></li>
        </ul>
    </div>
    <dir class="col-lg-1"><!-- Spaaaaace! --></dir>
    <div class="col-lg-9 col-sm-12 hlpf_newsborder">
        <!-- Load pages here... -->
    </div>
</div>
