var boatSelected;
var boatDirection = "horizontal";
var demo = document.getElementById("demo");
var boatBoard = document.getElementById("boatBoard");
var boatBoardX = boatBoard.rows[0].cells.length;
var boatBoardY = boatBoard.rows.length;
var pinBoard = document.getElementById("pinBoard");
var boatBoardX = boatBoardX
var boatBoardY = boatBoardY;
var boatBoxes = [];
var pinBoxes = [];
for (var i = 0; i < boatBoard.rows.length; i++) {
    for (var j = 0; j < boatBoard.rows[i].cells.length; j++) {
        boatBoxes.push(boatBoard.rows[i].cells[j]);
        pinBoxes.push(pinBoard.rows[i].cells[j]);
    }
}
var placedBoats = [];
var activeCells = [];
var boatTypeQuantities =
{
    boat2Quantity: 0,
    boat3Quantity: 0,
    boat4Quantity: 0,
    boat5Quantity: 0
};
var totalBoatQuantity = 0;

var gameStarted = false;
var startInterval;
var turnInterval;


var whitePins = [];
var redPins = [];
var myTurn = false;

initializeGameRequest();

function initializeGameRequest() {
    //initialize gameStarted
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            gameStarted = !!+this.responseText;

            //check every few seconds if everyone pressed start game button
            if (!gameStarted) {
                startInterval = setInterval(startCheckbox, 2000);
            }
            else {
                turnInterval = setInterval(checkTurn, 2000);
            }
        }
    };
    xmlhttp.open("POST", "../includes/game.inc.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("checkStart=0");

    //initialize Boat quantities
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var gameJsonRaw = this.responseText;
            var gameJson = JSON.parse(gameJsonRaw);

            for (boatType in gameJson) {
                boatTypeQuantities[boatType + "Quantity"] = gameJson[boatType];
                totalBoatQuantity += parseInt(gameJson[boatType]);

                //update ship conatiner quantities
                if (document.getElementById(boatType + "Quantity")) {
                    document.getElementById(boatType + "Quantity").innerHTML = gameJson[boatType];
                }
            }
        }
    };
    xmlhttp.open("POST", "../includes/game.inc.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("boatLengths=0");

    //initialize Boats and Pins
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var gameJsonRaw = this.responseText;

            initializeGame(gameJsonRaw);
        }
    };
    xmlhttp.open("POST", "../includes/game.inc.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("initializeGame=0");
}

function initializeGame(gameJsonRaw) {
    //initialize Boats
    var gameJson = JSON.parse(gameJsonRaw);

    for (boatType in gameJson["boats"]) {
        for (boat in gameJson["boats"][boatType]) {
            placedBoat = [];
            for (coordinate in gameJson["boats"][boatType][boat]["coordinates"]) {
                placedBoat.push(document.getElementById("m" + gameJson["boats"][boatType][boat]["coordinates"][coordinate]));
            }
            placedBoats.push(placedBoat);
        }
    }

    colorBoatBoxes();

    //initialize Pins
    for (pin in gameJson["pins"]["white"]) {
        whitePins.push(document.getElementById("o" + gameJson["pins"]["white"][pin]));
    }

    for (pin in gameJson["pins"]["red"]) {
        redPins.push(document.getElementById("o" + gameJson["pins"]["red"][pin]));
    }

    colorPinBoxes();
}

document.getElementsByTagName("body")[0].addEventListener
    ("click",
        function () {
            if (event.target.tagName == "MAIN") {
                boatSelected = null;
                colorBoatBoxes();
            }
        }
    );

document.getElementsByTagName("body")[0].addEventListener
    ("wheel",
        function () {
            if (boatDirection == "horizontal") {
                boatDirection = "vertical";
            }
            else {
                boatDirection = "horizontal";
            }
        }
    );

function startCheckbox() {
    //Make startCheckbox apear only if all boats are placed
    if (placedBoats.length == totalBoatQuantity) {
        document.getElementById("startLabel").style.display = "block";
        document.getElementById("start").style.display = "block";
    }
    else {
        document.getElementById("startLabel").style.display = "none";
        document.getElementById("start").style.display = "none";
    }

    //Check if the game must start
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText) {
                clearInterval(startInterval);

                prepareToStartGame()
            }
        }
    };
    xmlhttp.open("POST", "../includes/game.inc.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("checkStart=0");
}

function prepareToStartGame() {
    gameStarted = true;
    document.getElementById("startLabel").style.display = "none";
    document.getElementById("start").style.display = "none";
    document.getElementById("ships").style.display = "none";

    turnInterval = setInterval(checkTurn, 2000);
}

//send start game button value to database
function sendStartGameCheckbox(checkbox) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.open("POST", "../includes/game.inc.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("start=" + checkbox.checked);

    //send boats's position in database
    if (checkbox.checked) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.open("POST", "../includes/game.inc.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        var coordinates = [];
        for (var i = 0; i < placedBoats.length; i++) {
            var tempArray = [];
            for (var j = 0; j < placedBoats[i].length; j++) {
                tempArray.push(placedBoats[i][j].id.substring(1));
            }
            coordinates.push(tempArray);
        }

        xmlhttp.send("coordinates=" + JSON.stringify(coordinates));

        setTimeout(
            function () {
                //add all boats in game's json
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.open("POST", "../includes/game.inc.php", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.send("addShippsInJson=0");
            }
            , 2000);
    }
}

function addOrRemoveBoat(x) {
    if (!gameStarted) {
        //check if highlited boatBoxes colide with existing boats
        var checkForOtherBoat = false;
        for (var i = 0; i < placedBoats.length; i++) {
            for (var j = 0; j < activeCells.length; j++) {
                if (placedBoats[i].includes(activeCells[j]))
                    checkForOtherBoat = true;
            }
        }

        //if there is a boat selected and no boat placed on the same spot
        if (boatSelected && !checkForOtherBoat) {
            //add boat in the list of placed boats
            placedBoats.push(activeCells);

            //decrease the quantity of the current boat type
            document.getElementById(boatSelected + "Quantity").innerHTML = boatTypeQuantities[boatSelected + "Quantity"] - 1;
            boatTypeQuantities[boatSelected + "Quantity"]--;

            if (parseInt(boatTypeQuantities[boatSelected + "Quantity"]) <= 0) {
                boatSelected = null;
            }
        }
        //if there is a boat placed on the same spot remove placed boat 
        else {
            for (const [i, boat] of placedBoats.entries()) {
                for (coordinate of boat) {
                    if (coordinate.id == x.id) {
                        document.getElementById("boat" + boat.length + "Quantity").innerHTML = boatTypeQuantities["boat" + boat.length + "Quantity"] + 1;
                        boatTypeQuantities["boat" + boat.length + "Quantity"]++;
                        placedBoats.splice(i, 1);
                        break;
                    }
                }
            }
        }

        colorBoatBoxes();
    }
}

function highlightBoatArea(x) {

    if (!gameStarted) {
        colorBoatBoxes();

        if (boatSelected) {
            var tempBoatDirection = boatDirection;
            var coordinates = x.id.substring(1).split(",", 2);
            var x = parseInt(coordinates[0]);
            var y = parseInt(coordinates[1]);
            var boatSize = parseInt(boatSelected.replace("boat", ""));

            for (var i = 0; i < boatSize; i++) {
                if (tempBoatDirection == "horizontal") {
                    if (y + i < boatBoardX) {
                        activeCells.push(document.getElementById("m" + x.toString() + "," + (y + i).toString()));
                    }
                    else {
                        activeCells.push(document.getElementById("m" + x.toString() + "," + (boatBoardX - 1 - i).toString()));
                    }
                }
                else {
                    if (x + i < boatBoardY) {
                        activeCells.push(document.getElementById("m" + (x + i).toString() + "," + y.toString()));
                    }
                    else {
                        activeCells.push(document.getElementById("m" + (boatBoardX - 1 - i).toString() + "," + y.toString()));
                    }
                }
            }

            for (var i = 0; i < activeCells.length; i++) {
                activeCells[i].style.backgroundColor = "red";
            }
        }
    }
}

function colorBoatBoxes() {
    //clear activeCells array
    activeCells = [];

    //reset Cells's color
    for (var i = 0; i < boatBoxes.length; i++) {
        if (placedBoats.length > 0) {
            var checkForBoatPlaced = false;
            for (var j = 0; j < placedBoats.length; j++) {
                if (placedBoats[j].includes(boatBoxes[i])) {
                    checkForBoatPlaced = true;
                }
            }
            if (checkForBoatPlaced) {
                boatBoxes[i].style.backgroundColor = "black";
            }
            else {
                boatBoxes[i].style.backgroundColor = "white";
            }
        }
        else {
            boatBoxes[i].style.backgroundColor = "white";
        }
    }
}

function selectBoat(x) {
    if (parseInt(boatTypeQuantities[x.id + "Quantity"]) > 0) {
        boatSelected = x.id;
    }
    colorBoatBoxes();
}

function highlightPinArea(x) {
    colorPinBoxes();

    if (gameStarted && myTurn) {
        colorPinBoxes()
        x.style.backgroundColor = "red";
    }
}

function colorPinBoxes() {
    for (pin of pinBoxes) {
        if (whitePins.includes(pin)) {
            pin.style.backgroundColor = "yellow";
        }
        else if (redPins.includes(pin)) {
            pin.style.backgroundColor = "red";
        }
        else {
            pin.style.backgroundColor = "white";
        }
    }
}

function sendPin(x) {
    if (gameStarted && myTurn) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                if (this.responseText) {
                    redPins.push(x);
                }
                else {
                    whitePins.push(x);
                }
            }
        };
        xmlhttp.open("POST", "../includes/game.inc.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("pinSent=" + x.id.substring(1));

        changeTurn();
    }
}

function checkTurn() {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText) {
                myTurn = true;
            }
            else {
                myTurn = false;
            }
        }
    };
    xmlhttp.open("POST", "../includes/game.inc.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("checkTurn=0");
}

function changeTurn() {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.open("POST", "../includes/game.inc.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("changeTurn=0");

    myTurn = false;

    colorPinBoxes()
}

function TestAjax() {
    request("TestAjax=0").onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("demo").innerHTML = this.responseText;
        }
    }
}
function request(x) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.open("POST", "../includes/game.inc.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(x);

    return xmlhttp;
}