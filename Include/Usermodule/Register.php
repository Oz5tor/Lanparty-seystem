<!-- Register Start -->
<div class="row">
    <div class="col-lg-12 hlpf_newsborder">
        <div class="row">
            <div class="col-lg-12 hlpf_large_news_box">
                <img class="img-responsive" src="Images/image-slider-5.jpg">
                <hr/> 
                <div class="hlpf_flex">
                    <div class="hlpf_news">
                        <div class="table-responsive">
                            <table class="table">
                                <form action="" method="post">
                                    <tr>
                                        <td>
                                            <label for="FullName">Fulde Navn:*</label>
                                            <input type="text" class="form-control" placeholder="Santa Claus" id="FullName" value="<?php echo $_SESSION['FullName']; ?>" required name="FullName">
                                        </td>
                                        <td><label for="Email">Email:*</label>
                                            <input type="email" class="form-control" id="Email" placeholder="Workshop@santa.chrismas" value="<?php echo $_SESSION['Email']; ?>" required name="Email">
                                        </td>
                                        <td><label for="Birthday">F&oslash;seldsdag:*</label>
                                            <input type="date" placeholder="DD/MM/YYYY" class="form-control" id="Birthday" value="<?php echo date("d/m/Y",strtotime($_SESSION['Birthday'])); ?>" required name="Birthday">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="Username">Brugernavn:*</label>
                                            <input type="text" placeholder="ImNotSanta" class="form-control" id="FullName" value="<?php echo $_SESSION['PreffereredUsername']; ?>" required name="Username">
                                        </td>
                                        <td>
                                            <label for="Password">Kodeord:*</label>
                                            <input type="password" class="form-control" id="Password" placeholder="Kodeord" required name="Password">
                                        </td>
                                        <td>
                                            <label for="CPasswoord">Bekr&aelig;ft Kodeord:*</label>
                                            <input type="password" class="form-control" id="CPasswoord" placeholder="Gentag Kodeord" required name="CPasswoord">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="Phone">Telefon:*</label>
                                            <input type="text" class="form-control" id="Phone" value=""  placeholder="feks: 11223344 eller +4511223344" required name="Phone">
                                        </td>
                                        <td>
                                            <label for="Address">Adresse:*</label>
                                            <input type="text" placeholder="feks Norpolen 42, 6.sal tv" class="form-control" id="FullName" value="" required name="Address">
                                        </td>
                                        <td>
                                            <label for="Zipcode">Postnumber:*</label>
                                            <input type="number" placeholder="1337" maxlength="4" class="form-control" id="Zipcode" value="" required name="Zipcode">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <label for="Bio">Profil tekst:</label>
                                            <textarea id="Bio" class="form-control" rows="5" name="Bio">
                                            </textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-inline">
                                                <label for="ToS">Brugerbetinelser:*</label>
                                            <input type="checkbox" class="form-control" id="ToS" value="" required name="ToS">
                                            </div>
                                        </td>
                                        <td>&nbsp;</td>
                                        <td class="text-center">
                                            <input type="submit" class="btn btn-default" name="Create_user">
                                        </td>
                                    </tr>
                                </form>    
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
    </div>
</div>
<!-- Register end -->
