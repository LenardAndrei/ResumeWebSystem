<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php'); 
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        .logout {
            text-align: right;
            margin: 10px 20px;
        }
        .logout a {
            display: inline-block;
            padding: 8px 14px;
            background: #4f46e5;
            color: #fff;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
        }
        .logout a:hover {
            background: #4338ca;
        }
    </style>
</head>
<body>
    <div class="logout">
        <a href="logout.php">Logout</a>
    </div>

    <div class = "resume-container">

        <?php
            $name = "Lenard Andrei V. Panganiban";
            $title = "Computer Science";
            $email = "andreipanganiban82@gmail.com";
            $phone = "0998-958-7442";
            $address = "Alalum, San Pascual, Batangas";
        ?>
        <div class="header">
            <div class="info">
                <div class="name">
                    <?php echo $name; ?>
                </div>
                <div class="title">
                    <?php echo $title; ?>
                </div>
                <div class="contact">
                    <div><?php echo $email; ?></div>
                    <div><?php echo $phone; ?></div>
                    <div><?php echo $address; ?></div>
                </div>
            </div>

            <div class="pic">
                <img src="formalpic.jpg" alt="picture">
            </div>
        </div>

        <hr>

        <div class="objective">
            <div>
                <?php echo "Computer Science student with a passion for web development, 
                seeking opportunities to apply skills in HTML, CSS, JavaScript, 
                and PHP to create responsive and user-friendly websites. Continuously 
                learning new technologies and improving programming abilities through 
                hands-on projects and experimentation."; ?>
            </div>
        </div> 

        <?php
            $age = "20";
            $sex = "Male";
            $bday = "October 1, 2004";
            $birthPlace = "Bauan, Batangas";
            $status = "Single";
            $nationality = "Filipino";
        ?>

        <div class="personalData">
            <div class="personalDataTitle">
            <div><?php echo "PERSONAL DATA"; ?></div>
            </div>
            <div class="content">
                <div class="label">
                    <div><?php echo "Age:"; ?></div>
                    <div><?php echo "Sex:"; ?></div>
                    <div><?php echo "Birthday:"; ?></div>
                    <div><?php echo "Birth Place:"; ?></div>
                    <div><?php echo "Civil Status:"; ?></div>
                    <div><?php echo "Nationality:"; ?></div>
                </div>

                <div class="infoTwo">
                    <div><?php echo $age; ?></div>
                    <div><?php echo $sex; ?></div>
                    <div><?php echo $bday; ?></div>
                    <div><?php echo $birthPlace; ?></div>
                    <div><?php echo $status; ?></div>
                    <div><?php echo $nationality; ?></div>
                </div>
            </div>
        </div>

        <div class="education">
            <div class="educTitle">
                <div><?php echo "EDUCATIONAL BACKGROUND"; ?></div>
            </div>

            <div class="college">
                <div class="collegeLabel">
                    <div><?php echo "College"; ?></div>
                </div>
                <div class="collegeInfo">
                    <div><?php echo "<b>Bachelor of Science in Computer Science</b>"; ?></div>
                    <div><?php echo "Batangas State University - Alangilan Campus"; ?></div>
                    <div><?php echo "August 2023 - Present"; ?></div>
                </div>
            </div>
        </div>

        <div class="skills">
            <div class="skillsTitle">
                <div><?php echo "SKILLS"; ?></div>
            </div>
            <div class="skillsInfo">
                <ul>
                    <li><b>Web Development:</b> HTML, CSS, PHP, Javascript</li>
                    <li><b>Programming:</b> Python, Java, C++, C#</li>
                    <li><b>Soft Skills:</b> Communication, Time Management, Adaptability, Teamwork & Collaboration</li>
                </ul>
            </div>
        </div>
    </div>
    
</body>
</html>
