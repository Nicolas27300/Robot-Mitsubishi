<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <script src="js/jquery-3.2.1.min.js"></script>
        <title>IHM Robot</title>
    </head>
    <body>
        <div class="container">
            <div class="section">
                <center><h1><u>IHM Robot version beta 0.3</u></h1><br>(On a dressé Mitsubishi)</center>
                <div class="row">
                    <div class="col-md-3">
                        <br><br><br><br><br><br><br>
                        <div id="button">
                            <input id="buttonConnexion" type="button" class="btn btn-success btn btn-primary btn-lg" value="Connexion">
                            <input id="buttonDeconnexion" type="button" class="btn btn-danger btn btn-primary btn-lg" value="Deconnnexion">
                            <input id="buttonReset" type="button" class="btn btn-info btn btn-primary btn-lg" value="Reset positions">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <input id="buttonJ1" type="button" class="btn btn-info btn btn-primary btn-lg" value="J1"><br><br>
                        <input id="buttonJ2" type="button" class="btn btn-info btn btn-primary btn-lg" value="J2"><br><br>
                        <input id="buttonJ3" type="button" class="btn btn-info btn btn-primary btn-lg" value="J3"><br><br>
                        <input id="buttonJ5" type="button" class="btn btn-info btn btn-primary btn-lg" value="J5"><br><br>
                        <input id="buttonJ6" type="button" class="btn btn-info btn btn-primary btn-lg" value="J6"><br>
                    </div>
                    <div class="col-md-2">
                        <br><br><br><br><br>
                        <div id="info"><center>Le robot n'est pas connecté.</center></div>
                    </div>
                    <div class="col-md-1">
                        <br><br><br>
                        <input id="buttonPlus" type="button" class="btn btn-info btn btn-primary btn-lg" value="+"><br><br><br>
                        <input id="buttonSTOPAlarm" type="button" class="btn btn-danger btn btn-primary btn-lg" value="STOP ALARM"><br><br><br>
                        <input id="buttonMoins" type="button" class="btn btn-info btn btn-primary btn-lg" value="-"><br>
                    </div>
                </div>
            </div>  
        </div>
    </body>

    <script type="text/javascript">
        var axe = 1;



        $("#buttonConnexion").click(function() {
            document.getElementById("info").innerHTML = 'Connexion en cours...<br><img src="pacman.gif"></img>';
            $.ajax({
                url: "controller.php?connexion",
                success: function(data) {
                    document.getElementById("info").innerHTML = "";
                    document.getElementById("info").innerHTML = "<center>" + data + "</center>";
                }
            });
        });

        $("#buttonJ1").click(function() {
            axe = 1;
            console.log(axe);
        });

        $("#buttonJ2").click(function() {
            axe = 2;
            console.log(axe);
        });

        $("#buttonJ3").click(function() {
            axe = 3;
            console.log(axe);
        });

        $("#buttonJ5").click(function() {
            axe = 5;
            console.log(axe);
        });

        $("#buttonJ6").click(function() {
            axe = 6;
            console.log(axe);
        });

        $("#buttonPlus").click(function() {
            $.ajax({
                url: "controller.php",
                dataType: 'html',
                type: 'GET',
                data: 'up=' + axe,
                success: function(data) {
                    console.log(data);
                    document.getElementById("info").innerHTML = "";
                    document.getElementById("info").innerHTML = "<center>" + data + "</center>";
                }
            });
        });

        $("#buttonMoins").click(function() {
            console.log("PLUS");
            $.ajax({
                url: "controller.php",
                dataType: 'html',
                type: 'GET',
                data: 'down=' + axe,
                success: function(data) {
                    console.log(data);
                    document.getElementById("info").innerHTML = "";
                    document.getElementById("info").innerHTML = "<center>" + data + "</center>";
                }
            });
        });

        $("#buttonSTOPAlarm").click(function() {
            console.log("STOP ALARM");
            $.ajax({
                url: "controller.php",
                type: 'GET',
                data: 'alarm',
                success: function(data) {
                    console.log(data);
                    document.getElementById("info").innerHTML = "";
                    document.getElementById("info").innerHTML = data;
                }
            });
        });
        
        $("#buttonDeconnexion").click(function(){
            console.log("Deconnexion");
            $.ajax({
                url: "controller.php",
                type: 'GET',
                data: 'deconnexion',
                success: function(data){
                    document.getElementById("info").innerHTML = "Le robot n'est pas connecté.";
                }
            });
        });
        
        $("#buttonReset").click(function(){
            console.log("Reset");
            $.ajax({
                url: "controller.php",
                type: 'GET',
                data: 'resetPos',
                success: function(data){
                    document.getElementById("info").innerHTML = "<center>" + data + "</center>";
                }
            });
        });
    </script>
</html>
