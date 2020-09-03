<?php
    session_start();
    include_once "pdo.php";
    include_once "util.php";
    // if(!isset($_POST['email']))
    //     $_SESSION['profile_id'] = $_GET['profile_id'];
    if ( ! isset($_SESSION['name'])){
        die("Not logged in");
    }
    if ( isset($_POST['cancel']) ) {
        header('Location: index.php');
        return;
    }
    if(isset($_POST['save'])){
        $submit = validate();
        $submitPos = validatePos();
        if ($submit === true && $submitPos === true && isset($_POST['email'])){
            $stmt = $pdo->prepare("UPDATE Profile SET first_name=:first_name, last_name = :last_name, email = :email, headline = :headline, summary = :summary WHERE profile_id = :profile_id");

            $stmt->execute(array(':first_name' => $_POST['first_name'], ':last_name' => $_POST['last_name'], ':email' => $_POST['email'],':headline' => $_POST['headline'],':summary' => $_POST['summary'], ':profile_id' => $_GET['profile_id']));
            $stmt = $pdo->prepare('DELETE FROM Position WHERE profile_id=:pid');
            $stmt->execute(array( ':pid' => $_GET['profile_id']));
            for($i=1; $i<=9; $i++) {
                if (! isset($_POST['year'.$i])|| ! isset($_POST['desc'.$i]))
                    continue;
                else{
                    $stmt = $pdo->prepare('INSERT INTO `Position`(`profile_id`, `rank`, `year`, `description`)VALUES ( :pid, :rank, :year, :description)');
                    $stmt->execute(array(
                    ':pid' => $_GET['profile_id'],
                    ':rank' => $i,
                    ':year' => $_POST['year'.$i],
                    ':description' => $_POST['desc'.$i])
                    );
                }
            }
            // unset($_SESSION['profile_id']);
            $_SESSION['success'] = 'Profile updated';
            $_SESSION['color'] = 'green';
            header('Location: index.php');
            return;
        }
        else if(isset($_POST['email'])){
            $_SESSION['error'] = ($submit !== true?$submit:$submitPos);
            $_SESSION['color'] = "red";
            header('Location: edit.php?profile_id=' . $_GET['profile_id']);
            return;
        }
    }
    $stmt = $pdo->prepare("SELECT * FROM Profile WHERE profile_id = :id");
    $stmt->execute(array(':id' => $_GET['profile_id']));
    $rows1 = $stmt->fetch(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html>
    <head>
    <title>Himanshu Likhar</title>
        <?php require_once "css.php" ?>
    </head>
    <body>
        <div class="container">
            <?php
                echo "<h1>Editing Profile for " . $_SESSION['name'] . "</h1>\n";
                flashMessages();
            ?>
            <form method="POST">
                <p>
                <label>First Name:</label>
                <input type="type" name="first_name" value=<?php echo $rows1->first_name;?>>
            </p>
            <p>
                <label>Last Name:</label>
                <input type="text" name="last_name" value=<?php echo $rows1->last_name;?>>
            </p>
            <p>
                <label>Email:</label>
                <input type="text" name="email" value=<?php echo $rows1->email;?>>
            </p>
            <label>Headline:</label><br/>
            <input type="text" name="headline" style="width: 40%" value=<?php echo $rows1->headline;?>>
            <p>
                <p>
                    <label>Summary:</label>
                </p>
                <textarea name="summary" rows="8" cols="80"><?php echo $rows1->summary;?></textarea>
            </p>
            <p>
                <label>Position: </label>
                <input type='button' value='+' id='addPos'>
            </p>
            <div id="position_fields">
                <?php
                    $sql = $pdo->prepare("SELECT * FROM Position WHERE profile_id = :id");
                    $sql->execute(array(':id' => $_GET['profile_id']));
                    $rows2 = $sql->fetchAll(PDO::FETCH_ASSOC);
                    // for
                    $countPos = 1;
                    $i=0;
                    foreach ($rows2 as $row){
                        echo '<div id="position' . $countPos . '"> <p>Year: <input type="text" name="year' . $countPos . '" value=' . $rows2[$i]['year'] . '> <input type="button" value="-" onclick="$(\'#position' . $countPos . '\').remove(); return false;"></p> <textarea name="desc' . $countPos . '" rows="8" cols="80" spellcheck="false">' . $rows2[$i]['description'] . '</textarea></div>';
                        $countPos++;
                        $i++;
                    }
                    // die($countPos . '');
                ?>
            </div>
            <p>
                <input type="submit" name="save" value="Save">
                <input type="submit" name="cancel" value="Cancel">
            </p>
            </form>
            <script type="text/javascript">
            countPos = 0;
            $(document).ready(function(){
                window.console && console.log("Adding position " + countPos);
                $('#addPos').click(function(event){
                    event.preventDefault();
                    if(countPos>=9){
                        alert("Maximum of nine position entries exceeded");
                        return;
                    }
                    countPos++;
                    $('#position_fields').append(
                        '<div id="position' + countPos + '"> \ <p>Year: <input type="text" name="year' + countPos + '" value=""/> \ <input type="button" value="-" \ onclick="$(\'#position' + countPos + '\').remove(); return false;"></p> \ <textarea name="desc' + countPos + '" rows="8" cols="80" spellcheck="false"></textarea></div>'
                    );
                });
            });
    </script>
        </div>
    </body>
</html>