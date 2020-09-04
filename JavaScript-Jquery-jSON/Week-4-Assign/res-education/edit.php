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
        $submitEdu = validateEdu();
        if ($submit === true && $submitPos === true && $submitEdu === true && isset($_POST['email'])){
            $profSuccess = updateProfile($pdo);
        if($profSuccess !== true)
            die($profSuccess);
        $profile_id = $_GET['profile_id'];

        $deletePos = deletePos($pdo, $profile_id);
        if($deletePos !== true)
            die($deleteEdu);

        $posSuccess = insertPosition($pdo, $profile_id);
        if($posSuccess !== true)
            die($posSuccess);

        $deleteEdu = deleteEdu($pdo, $profile_id);
        if($deleteEdu !== true)
            die($deleteEdu);

        $eduSuccess = insertEducation($pdo, $profile_id);
        if($eduSuccess !== true)
            die($eduSuccess);

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
            <input type="text" name="headline" style="width: 50%" value='<?php echo $rows1->headline?>' >
            <p>
                <p>
                    <label>Summary:</label>
                </p>
                <textarea name="summary" rows="8" cols="80"><?php echo $rows1->summary;?></textarea>
            </p>
            <p>
                <label>Education: </label>
                <input type='button' value='+' id='addEdu'>
            </p>
            <div id="edu_fields">
                <?php
                    $sql = $pdo->prepare("SELECT * FROM Education WHERE profile_id = :id");
                    $sql->execute(array(':id' => $_GET['profile_id']));
                    $rows2 = $sql->fetchAll(PDO::FETCH_ASSOC);
                    $countEdu = 1;
                    foreach ($rows2 as $row){
                        $name = loadEdu($pdo, $row['institution_id']);
                        echo '<div id="edu' . $countEdu . '"> <p>Year: <input type="text" name="edu_year' . $countEdu . '" value="' . $row['year'] . '"/> <input type="button" value="-" onclick="$(\'#edu' . $countEdu . '\').remove(); return false;"></p> <p><label>School: </label> <input class="school" type="text" size="60" name="edu_school' . $countEdu . '" value="' . $name .'"/></p></div>';
                    }
                ?>
            </div>
            <p>
                <label>Position: </label>
                <input type='button' value='+' id='addPos'>
            </p>
            <div id="position_fields">
                <?php
                    $sql = $pdo->prepare("SELECT * FROM Position WHERE profile_id = :id");
                    $sql->execute(array(':id' => $_GET['profile_id']));
                    $rows2 = $sql->fetchAll(PDO::FETCH_ASSOC);
                    $countPos = 1;
                    foreach ($rows2 as $row){
                        echo '<div id="edu' . $countPos . '"> <p>Year: <input type="text" name="year' . $countPos . '" value=' . $row['year'] . '> <input type="button" value="-" onclick="$(\'#position' . $countPos . '\').remove(); return false;"></p> <textarea name="desc' . $countPos . '" rows="8" cols="80" spellcheck="false">' . $row['description'] . '</textarea></div>';
                    }
                ?>
            </div>
            <p>
                <input type="submit" name="save" value="Save">
                <input type="submit" name="cancel" value="Cancel">
            </p>
            </form>
        </div>
    </body>
</html>
<script type="text/javascript">
    countPos = 0;countEdu = 0;
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
    $(document).ready(function(){
        window.console && console.log("Adding education " + countEdu);
        $('#addEdu').click(function(event){
            event.preventDefault();
            if(countEdu>=9){
                alert("Maximum of nine Educatoin entries exceeded");
                return;
            }
            countEdu++;
            $('#edu_fields').append(
                '<div id="edu' + countEdu + '"> \ <p>Year: <input type="text" name="edu_year' + countEdu + '" value=""/> \ <input type="button" value="-" \ onclick="$(\'#edu' + countEdu + '\').remove(); return false;"></p> \ <p><label>School: </label> <input class="school" type="text" size="60" name="edu_school' + countEdu + '" value=""/></p></div>'
            );
            $('.school').autocomplete({source: "school.php"});
        });
    });
</script>