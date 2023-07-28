<nav class="navbar navbar-fixed" id="LanCMSmenu" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <a class="visible-xs navbar-brand" href="#">
                <img alt="HLParty" class="img-responsive" src="Images/logo-brand.png">
            </a>
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <i class="fa fa-bars fa-2x" aria-hidden="true" style="color: white;"></i>
            </button>
        </div>
        <div class="collapse navbar-collapse LanCMSwhite_text">
            <ul class="nav navbar-nav">
                <li class="active"><a href="index.php">Forside</a></li>
                <li class="dropdown" id="LanCMSmenu">
                  <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">HLPF Forening<span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li><a href="?page=Om Hovedstadens Lan Party Forening">Om os</a></li>
                    <li><a href="?page=Forenings FAQ">Forenings FAQ</a></li>
                    <li><a href="?page=Foreningens Dokumenter">Foreningens parpirer</a></li>
                  </ul>
            </ul>    
            <ul class="nav navbar-nav">
            <li class="dropdown" id="LanCMSmenu">
                  <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">HLParty <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li><a href="?page=Event">Information</a></li>
                    <li><a href="?page=Regler">Regler</a></li>
                    <li><a href="?page=Crew">Crew</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="?page=Kiosk">Kantinen tilbyder</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="?page=Yderligere information">Yderliger information</a></li>
                    <li><a href="?page=Find Os">Find Os</a></li>
                    <li><a href="?page=Huskeliste">Huskeliste</a></li>
                  </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav">
              <li><a href="?page=Newsarchive">Nyheds arkiv</a></li>
            </ul>
            <?php if (($page == "OSU") || (isset($_SESSION["UserID"]))) {}else{?>
            <ul class="nav navbar-nav navbar-right">
              <li><a id="oa_social_login_link">Login / Opret</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li><a href="index.php?page=OSU">Aktiver Gammel Bruger </a></li>
            </ul>

            <?php } ?>
                <!-- <li><a href="?page=Kontakt">Kontakt os</a></li>-->
            
        </div><!--.nav-collapse -->

        <!-- ===================================== -->

        <?php if (($page == "OSU") || (isset($_SESSION["UserID"]))) {}else{?>
        <!-- The plugin will be displayed when clicking on this link //-->
        <script type="text/javascript">
        var _oneall = _oneall || [];
        _oneall.push(['social_login', 'set_callback_uri', 'https://<?php echo $ROOTURL; ?>Include/oneall_hlpf/oneall_callback_handler.php']);
        _oneall.push(['social_login', 'set_providers', ['battlenet', 'discord', 'steam', 'twitch']]);
        _oneall.push(['social_login', 'set_grid_sizes', [4,1]]);
        _oneall.push(['social_login', 'attach_onclick_popup_ui', 'oa_social_login_link']);
        </script>
        <?php } ?>

        <!-- ===================================== -->
    </div>
</nav>
