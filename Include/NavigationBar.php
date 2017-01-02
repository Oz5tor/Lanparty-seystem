<nav class="navbar navbar-fixed" id="hlpf_menu" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <a class="visible-xs navbar-brand" href="#">
                <img alt="HLParty" class="img-responsive" src="Images/logo-brand.png">
            </a>
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <i class="fa fa-bars fa-2x" aria-hidden="true"></i>
            </button>
        </div>
        <div class="collapse navbar-collapse hlpf_white_text">
            <ul class="nav navbar-nav ">
                <li class="active"><a href="index.php">Forside</a></li>
                <li><a href="?page=Om_Os">Om os</a></li>
                <li class="dropdown" id="hlpf_menu">
                  <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">HLParty <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li><a href="?page=Information">Information</a></li>
                    <li><a href="?page=Regler">Regler</a></li>
                    <li><a href="?page=Crew">Crew</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="?page=Kiosk">Kantinen tilbyder</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="?page=Yderligere_information">Yderliger information</a></li>
                    <li><a href="?page=Find_Os">Find Os</a></li>
                    <li><a href="?page=Huskeliste">Huskeliste</a></li>
                  </ul>
                </li>
                <li><a href="?page=Kontakt">Kontakt os</a></li>
                <?php if(isset($_SESSION['UserID'])){ ?>
                <li class="dropdown" id="hlpf_menu">
                  <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Min profil <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                      <li><a href="?page=EditMyProfile">Ret min profil</a></li>
                  </ul>
                </li>
                <?php } ?>
                      
            </ul>
        </div><!--.nav-collapse -->
    </div>
</nav>
