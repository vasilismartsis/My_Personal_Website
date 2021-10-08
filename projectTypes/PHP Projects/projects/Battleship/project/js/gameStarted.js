function highlightPinArea(x) {
    colorPinBoxes();

    if (gameStarted && myTurn && !whitePins.includes(x) && !redPins.includes(x)) {
        x.style.backgroundColor = "blue";
    }
}

function colorPinBoxes() {
    for (pin of pinBoxes) {
        if (whitePins.includes(pin)) {
            pin.style.backgroundColor = "green";
        }
        else if (redPins.includes(pin)) {
            pin.style.backgroundColor = "red";
        }
        else {
            pin.style.backgroundColor = "white";
        }
    }

    for (boat in boatsWrecked) {
        for (coordinate in boatsWrecked[boat]) {
            document.getElementById("o" + boatsWrecked[boat][coordinate]).style.backgroundColor = "orange";
        }
    }
}

function getBoatsWrecked() {
    if (gameStarted) {
        xmlhttpRequest("getBoatsWrecked=0").onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                if (this.responseText != null) {
                    var gameJsonRaw = this.responseText;
                    var gameJson = JSON.parse(gameJsonRaw);

                    boatsWrecked = gameJson;

                    colorPinBoxes();
                }
            }
        };
    }
}

function getMyBoatPins() {
    if (gameStarted) {
        xmlhttpRequest("getMyBoatPins=0").onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                var gameJsonRaw = this.responseText;
                var gameJson = JSON.parse(gameJsonRaw);

                myBoatsHit = gameJson;

                colorBoatBoxes();
            }
        };
    }
}

function sendPin(x) {
    if (gameStarted && myTurn && !whitePins.includes(x) && !redPins.includes(x)) {
        xmlhttpRequest("pinSent=" + x.id.substring(1)).onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                if (this.responseText) {
                    redPins.push(x);
                }
                else {
                    whitePins.push(x);
                }
            }
        };

        changeTurn();
    }
}

function checkTurn() {
    xmlhttpRequest("checkTurn=0").onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText) {
                myTurn = true;
            }
            else {
                myTurn = false;
            }

            setPlayerTurn();
            getMyBoatPins();
            setScore();
            checkIfLost();
            checkForWinner();
        }
    };
}

function changeTurn() {
    xmlhttpRequest("changeTurn=0");

    myTurn = false;

    getBoatsWrecked();

    colorPinBoxes();
}

function checkIfLost() {
    totalBoatBoxesCount =
        parseInt(originalBoatTypeQuantities["boat2Quantity"]) * 2 +
        parseInt(originalBoatTypeQuantities["boat3Quantity"]) * 3 +
        parseInt(originalBoatTypeQuantities["boat4Quantity"]) * 4 +
        parseInt(originalBoatTypeQuantities["boat5Quantity"]) * 5;

    if (myBoatsHit.length != 0 && totalBoatBoxesCount != 0 && myBoatsHit.length == totalBoatBoxesCount) {
        lose();
    }
}

function lose() {
    clearInterval(turnInterval);

    lost = true;

    xmlhttpRequest("lose=0");

    winInterval = setInterval(checkForWinner, 2000);

    document.getElementById("boatBoard").style.display = "none";
    document.getElementById("pinBoard").style.display = "none";
    document.getElementById("playerTurn").style.display = "none";
}

function checkForWinner() {
    xmlhttpRequest("checkForWinner=0").onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText) {
                clearInterval(turnInterval);
                clearInterval(winInterval);
                document.getElementById("boatBoard").style.display = "none";
                document.getElementById("pinBoard").style.display = "none";
                document.getElementById("playerTurn").style.display = "none";

                for (tr of document.getElementById("scoreTable").childNodes[0].children) {
                    for (td of tr.children) {
                        if (td.innerHTML == this.responseText) {
                            td.style.backgroundColor = "lightgreen";
                            td.nextSibling.style.backgroundColor = "lightgreen";
                        }
                    }
                }

                alert("THE WINNER IS: " + this.responseText);
            }
        }
    };
}

function setPlayerTurn() {
    xmlhttpRequest("setPlayerTurn=0").onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            playerTurn = this.responseText;
            document.getElementById("playerTurn").innerHTML = "Now it's the turn of player: " + playerTurn;
        }
    };
}

function setScore() {
    xmlhttpRequest("setScore=0").onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var gameJsonRaw = this.responseText;
            var gameJson = JSON.parse(gameJsonRaw);

            for (playerId in gameJson) {
                if (gameJson[playerId]) {
                    document.getElementById(playerId + "Score").innerHTML = gameJson[playerId].length;
                }
            }
        }
    };
}

function Test() {
    console.log("test");
}