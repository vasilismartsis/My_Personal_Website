**This project was created for education purposes for a class of International Hellenic University*

# English Description 
** ADISE19_AdiseDreamTeam **

The Game:

The project is a website that contains a variation of the board game Naval Battle.

Original game description:

The object of the game is for one player to try to sink his opponent's boats first.
The game is normally for 2 people.
The game includes:
Two tables (one main and one auxiliary table) In 100 squares
that is, 10 vertical columns (A, B, C, D, E) and 10 horizontal columns (1,2,3,4,5).
Six warships: 1 size 2 ship, 2 size 3 ships, 1 size 4 ship, 1 size 5 ship.

Each player places his ships on the first board and when all the players have placed all their ships the game starts.
Then the first player "shoots" a square of the second board and if he succeeds another player's ship a red pin is placed on
Square shot of a different pin pin. If his opponent succeeds a ship then the player places a pin on
table with his ships. The ship that has been shot changes color so that players know how many ships are left in the opponent.

The winner is the one who will destroy all the enemy ships.

Description of variants implemented:

1) Multiple simultaneously active games.

2) Scoreboard.

2) More than 2 players in each game. The winner is the last one who has the remaining ships.

3) Customized game board size.

4) Custom number of ships.

5) The player can log out and continue the game from where he left it at any time.

The API:

The user can get the information while playing by doing the following:

    var xmlhttp = new XMLHttpRequest ();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            console.log (this.responseText);
        }
    };
    xmlhttp.open ("POST", "../api/gameJson.php", true);
    xmlhttp.setRequestHeader ("Content-type", "application / x-www-form-urlencoded");
    xmlhttp.send ("gameId = (Game ID)");

Where (Game ID) the id of the game from which the user wants to receive information must be entered.

It can get information about all the games: POST / api / gameJson.

Alternatively it can get the json while playing by setting the url to HOME / json / game (Game ID) .json.
It can get the json of all the game by setting the url to HOME / json /.

API Description:

The game API is as follows:

"playerN": {
        "pins": pins
            "white": ["x, y"],
            "red": ["x, y"]
        },
        "boats": {
            "boat (SIZE)": {
                "b (NUMBER)": {
                    "coordinates": [
                        "x, y",
                        "x, y"
                    ]
                }
            }
        }
    }
}

Where playerN, N is the player ID.

Inside the pins there are white and red tables that fill depending on where the player has shot. If an aloe ship succeeds, it fills the red. If it fails, it fills the White.

Inside the boats are the Boat objects (SIZE), where (SIZE) is the size of each ship.

Inside the Boat (SIZE) there are objects b (NUMBER), where (NUMBER) is the serial number of the ship of this size.

Inside b (NUMBER) is the list of coordinates containing the ship's coordinates.

Demo Page
You can download locally or visit the page: https://users.iee.ihu.gr/~it154489/

# Greek Description

Το Game:

Το project πρόκειται για μια ιστοσελίδα που περιέχει μια παραλλαγή του επιτραπέζιου παιχνιδιού Ναυμαχία.

Περιγραφή original παιχνιδιού:

Σκοπός του παιχνιδιού είναι να προσπαθήσει ο ένας παίκτης να βυθίσει πρώτος τα σκάφη του αντιπάλου του.
Το παιχνίδι κανονικά είναι για 2 άτομα.
Το παιχνίδι περιλαµβάνει:
∆ύο πίνακες (ένα κύριο και ένα ίδιο βοηθητικό πίνακα) µε 100 τετράγωνα
δηλαδή 10 κάθετες στήλες (Α,Β,Γ,∆,Ε) και 10 οριζόντιες γραµµές (1,2,3,4,5).
Έξι πολεµικά πλοία: 1 πλοίο μεγέθους 2, 2 πλοία μεγέθους 3, 1 πλοίο μεγέθους 4, 1 πλοίο μεγέθους 5.

Κάθε παίκτης τοποθετεί τα πλοία του στον πρώτο πίνακα και όταν όλοι οι παίκτες έχουν τοποθετήσει όλα τα πλοία τους το παιχνίδι ξεκινάει.
Τότε ο πρώτος παίκτης "πυροβολεί" ένα τετράγωνο του δεύτερου πίνακα και αν πετύχει πλοίο άλλου παίκτη τοποθετείται κόκκινη καρφίτσα στο
τετράγωνο που πυροβόλησε αλλιώς τοποθετεί άσπρη καρφίτσα. Άμα ο αντίπαλος του πετύχει ένα πλοίο τότε ο παίκτης τοποθετεί μια καρφίτσα στον
πίνακα με τα πλοία του. Το πλοίο που έχει πυροβοληθεί ολόκληρο αλλάζει χρώμα ώστε η παίκτες να ξέρουν πόσα πλοία έχουν μείνει στον αντίπαλο.

Νικητής είναι αυτός μου θα εξοντώσει όλα τα πλοία του αντιπάλου.

Περιγραφή παραλλαγών που υλοποιήθηκαν:

1) Πολλαπλά ταυτόχρονα ενεργά παιχνίδια.

2) Scoreboard.

2) Περισσότεροι από 2 παίκτες σε κάθε παιχνίδι. Νικητής είναι ο τελευταίος που έχει εναπομείναντα πλοία.

3) Προσαρμοσμένο μέγεθος ταμπλό παιχνιδιού.

4) Προσαρμοσμένος αριθμός πλοίων.

5) O παίκτης μπορεί να αποσυνδεθεί και να συνεχίσει το παιχνίδι από εκεί που το άφησε οποιαδήποτε στιγμή.

Το API:

Ο χρήστης μπορεί να πάρει τις πληροφορίες ενώς παιχνιδιού εκτελόντας το παρακάτω:

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
        }
    };
    xmlhttp.open("POST", "../api/gameJson.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("gameId=(ID Παιχνιδιού)");

Όπου (ID Παιχνιδιού) θα πρέπει να υσαχθεί το id του παιχνιδιού από το οποίο θέλει να λάβει πληροφορίες ο χρήστης.

Μπορεί να πάρει πληροφορίες για όλα τα παιχνίδια: POST /api/gameJson.

Ενναλακτικά μπορεί να πάρει το json ενώς παιχνιδιού, θέτοντας την url σε ΗΟΜΕ/json/game(ID Παιχνιδιού).json.
Mπορεί να πάρει τα json όλων των παιχνιδιού, θέτοντας την url σε ΗΟΜΕ/json/.

Περιγραφή API:

Το API ενώς παιχνιδιού έχει τη παρακάτω μορφή:

"playerN": {
        "pins": {
            "white": ["x,y"],
            "red": ["x,y"]
        },
        "boats": {
            "boat(SIZE)": {
                "b(NUMBER)": {
                    "coordinates": [
                        "x,y",
                        "x,y"
                    ]
                }
            }
        }
    }
}

Όπου playerN, Ν το ID του παίκτη.

Mέσα στο pins υπάρχουν οι πίνακες white και red που γεμίζουν ανάλογα με το πού έχει πυροβολίσει ο παίκτης. Άμα πετύχει πλοίο αλουνού, γεμίζει το red. Άν δέν πετύχει, γεμίζει το White.

Μέσα στο boats υπάρχουν τα αντικείμενα Boat(SIZE), όπου (SIZE) το μέγεθος του κάθε πλοίου.

Μέσα στο Boat(SIZE) υπάρχουν τα αντικείμενα b(NUMBER), όπου (NUMBER) ο αύξον αριθμός του πλοίου αυτού του μεγέθους.

Μέσα στο b(NUMBER) υπάρχει η λίστα coordinates που περιέχει τις συντεταχμένες του πλοίου.

Demo Page
Μπορείτε να κατεβάσετε τοπικά ή να επισκευτείτε την σελίδα: https://users.iee.ihu.gr/~it154489/
