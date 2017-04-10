<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel='stylesheet' href='css/main.css'/>
        <link rel="stylesheet" type="text/css"
              href="https://fonts.googleapis.com/css?family=Open-Sans|Montserrat"/>
        <title>Find Your TV Show</title>
    </head>
    <body>

        <?php
        // Prepare all PDO data to be used later
        $host = 'yujua.dev.fast.sheridanc.on.ca';
        $db   = 'yujua_tv_shows';
        $user = 'yujua_yujua';
        $pass = '(kagu)(sess)0.o';
        $charset = 'utf8';
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $pdo = new PDO($dsn, $user, $pass, $opt);

        // debug function
        function dump($el)
        {
            echo "<pre><div style='text-align=left';>";
            var_dump($el); 
            echo "</pre></div>";
        }

        function strip_input($el) {
            $el = trim($el);
            $el = stripslashes($el);
            $el = htmlspecialchars($el);
            return $el;
        }
        
        if (isset($_GET['title-input'])) {
            $tv_input = "%" . strip_input($_GET["title-input"]) . "%";
        } else {
            $tv_input = "%";
        }

        $sql = "SELECT
            tv_show.tvid, 
            tv_show.title AS title,
            genre.genre AS genre,
            date_aired.date_aired AS date_aired
            FROM `tv_show`
            INNER JOIN `genre`
            ON (tv_show.genre = genre.gid)
            INNER JOIN `date_aired`
            ON (tv_show.date_aired = date_aired.did)
            WHERE tv_show.title LIKE :input";
        try{
            $query = $pdo->prepare($sql);
            $query->bindParam(':input', $tv_input);
            $result = $query->execute();
        } catch( PDOException $err ) {
            echo "ERROR: " . $err->getMessage();
        }

        if ($result) {
            $data = $query->fetchAll();
        }
        
        // all genres from database; to be used later
        $sql1 = "SELECT gid, genre FROM `genre` ORDER BY genre ASC";
        try {
            $query1 = $pdo->prepare($sql1);
            $result1 = $query1->execute();
        } catch ( PDOException $err ) {
            echo "ERROR: " . $err->getMessage();
        }
        $data1 = $query1->fetchAll();
    
        ?>

        <!--------------- HEADER --------------->
        
        <header class='container' id='header-container'>
            <div class='container form-container' id='tv-search-container'>
                <!-- search for tv show by title -->
                <form id='tv-search-form' method="GET" action="default.php">
                    <input type='text' name='title-input' placeholder='Search for TV Show'/>
                    <input type='submit'/>
                </form>
            </div>
        </header> <!-- end header-container -->

        <!--------------- MAIN --------------->
        <main class='container' id='main-container'>
            <div class='flex-container'>
                <?php
                
                // display all tv show content
                for ($i=0; $i < sizeof($data); $i++) {
                    echo "<div class='flex-item'>";
                    echo "<img src='images/" . tv_title($data[$i]['title']) . ".jpg' alt='" . "image of " . tv_title($data[$i]['title']) . "'/>";
                    echo "<div class='flex-item-text-div'><div class='flex-item-text'><h3>" . $data[$i]['title'] . "</h3>";
                    echo "<p>" . $data[$i]['genre'] . "</p>";
                    echo "<br>" . $data[$i]['date_aired'] . "</div></div></div>";
                }

                // function that strips tv show titles of spaces
                // use this to add specific tv show id's to flex-items
                function tv_title($input) {
                    $output = '';
                    for ($i=0; $i<strlen($input); $i++) {
                        if ($input[$i] !== " ") {
                            $output .= $input[$i];
                        }
                    }
                    return $output;
                }

                ?> 
            </div> <!-- end flex-container -->

            <!--------------- SUBMIT SUGGESTION --------------->     
            <div class='container' id='main-two-container'>
                <div class='container form-container' id='suggestion-container'>
                    <h3>Don't see a tv show you like?</h3>
                    <p>Submit a suggestion!</p>
                    <form id='suggestion-form' method='GET' action='default.php'>
                        <input type='text' name='title' placeholder='TV Show Title'/>
                        <input type='text' name='date-aired' placeholder='Premiere Year (eg. 2016)'/>
                        <select name='genre'>
                            <option value='genre'>Genre</option>
                            <?php 
                            foreach( $data1 as $row ) {
                                // display row:
                                // list all genres except 'other', then list 'other' at the end
                                if ($row['genre'] != 'other') {
                                    echo "<option value='";
                                    echo $row['gid'];
                                    echo "'>";
                                    echo $row['genre'];
                                    echo "</option>";
                                }
                            }
                            ?>
                            <option value='510'>other</option>
                        </select>
                        <input type='submit'/>
                    </form>
                </div> <!-- end form-container -->

                <?php 

                // inserting into database
                if (isset($_GET['title'])) {
                    $title = $_GET['title'];
                    echo $title;
                }
                if (isset($_GET['date-aired'])) {
                    $date_aired = $_GET['date-aired'];
                    echo $date_aired;
                }
                if (isset($_GET['genre'])) {
                    $gid = $_GET['genre'];
                    echo $gid;
                }
                if (isset($_GET['title']) && isset($_GET['date-aired']) && isset($_GET['genre'])) {

                    // because we asked user to manually enter a date_aired into the form, we do not have a did corresponding to each date_aired
                    // we need to check if user's date_aired exists in the database already - if so, then did exists
                    // if user's date_aired does not exist in database, we must create a new did
                    $sqlCheck = "SELECT did, date_aired FROM `date_aired`;";
                    try {
                        $queryCheck = $pdo->prepare($sqlCheck);
                        $resultcheck = $queryCheck->execute();
                    } catch ( PDOException $err ) {
                        echo "ERROR: " . $err->getMessage();
                    }
                    $dataCheck = $queryCheck->fetchAll();

                    // loop through premiere dates 
                    $present = false;
                    foreach( $dataCheck as $rowCheck ) {
                        if ($rowCheck['date_aired'] == $date_aired) {
                            $present = true;
                            $did = $rowCheck['did'];
                        } 
                    }

                    // if date was NOT present
                    if ($present == false) {

                        // insert new date
                        $sqlDateAired = "INSERT INTO `date_aired` (`date_aired`) VALUES (?);";
                        $queryDateAired = $pdo->prepare($sqlDateAired);
                        $resultDateAired = $queryDateAired->execute([$date_aired]);

                        // find current largest did in date_aired table
                        $sqlMax = "SELECT MAX(did) FROM `date_aired`;";
                        $queryMax = $pdo->prepare($sqlMax);
                        $resultMax = $queryMax->execute();
                        $dataMax = $queryMax->fetchAll();
                        // set new did
                        $did = $dataMax[0]["MAX(did)"] + 1;
                    } 

                    // if date was present
                    else if ($present == true) {

                        $present = false; // reset to false
                    }

                    $sql2 = "INSERT INTO `tv_show` (`title`, `genre`, `date_aired`) VALUES (?, ?, ?);";
                    $query2 = $pdo->prepare($sql2);
                    $result2 = $query2->execute([$title, $gid, $did]);
                }
                ?>
            </div> <!-- end suggestion-container -->

        </main> <!-- end main-container -->

    </body>
</html>

