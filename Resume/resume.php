<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php'); 
    exit;
}

require_once 'db.php';

$user_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM user_resume WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $resume = $stmt->fetch(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    die("Error fetching data: " . $e->getMessage());
}

if (!$resume) {
    $resume = [
        'full_name' => 'Lenard Andrei V. Panganiban',
        'email' => 'andreipanganiban82@gmail.com',
        'title' => 'Computer Science',
        'address' => 'Alalum, San Pascual, Batangas',
        'phone' => '0998-958-7442',
        'description' => 'Computer Science student with a passion for web development, seeking opportunities
                          to apply skills in HTML, CSS, JavaScript, and PHP to create responsive and user-friendly
                          websites. Continously learning new technologies and improving programming abilities through
                          hands-on projects and experimentation.',
        'age' => '21',
        'sex' => 'Male',
        'birthday' => '2004-10-01',
        'birth_place' => 'Bauan, Batangas',
        'civil_status' => 'Single',
        'nationality' => 'Filipino',
        'school' => 'Batangas State University - Alangilan',
        'course_program' => 'Bachelor of Science in Computer Science',
        'date_started' => 'August 2023',
        'date_ended' => '',
        'skills_web_dev' => 'HTML, CSS, PHP, Javascript',
        'skills_ui_ux' => 'Figma (Wireframing, Prototyping, Interface Design)',
        'skills_programming' => 'Python, Java, C++, C#',
        'skills_soft' => 'Communication, Time Management, Adaptability, Teamwork & Collaboration',
        'photo' => 'formalpic.jpg'
    ];
}


$name = $resume['full_name'];
$title = $resume['title'];
$email = $resume['email'];
$phone = $resume['phone'];
$address = $resume['address'];
$age = $resume['age'];
$sex = $resume['sex'];
$bday = $resume['birthday'] ? date('F j, Y', strtotime($resume['birthday'])) : '';
$birthPlace = $resume['birth_place'];
$status = $resume['civil_status'];
$nationality = $resume['nationality'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        .resume_button {
            display: flex;
            flex-wrap: wrap; 
            justify-content: right;
        }
        .button {
            text-align: right;
            margin: 10px 10px;
        }
        .button a {
            display: inline-block;
            padding: 8px 14px;
            background: #4f46e5;
            color: #fff;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
        }
        .button a:hover {
            transform: scale(1);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
    </style>
</head>
<body>
    <div class = "resume_button">
        <div class="button">
            <a href="edit_resume.php">Edit Resume</a>
        </div>

        <div class="button">
            <a href="resume_home.php">← Back to Home</a>
        </div>

    </div>

    <div class = "resume-container">

        <div class="header">
            <div class="info">
                <div class="name">
                    <?php echo htmlspecialchars($name); ?>
                </div>
                <div class="title">
                    <?php echo htmlspecialchars($title); ?>
                </div>
                <div class="contact">
                    <div><?php echo htmlspecialchars($email); ?></div>
                    <div><?php echo htmlspecialchars($phone); ?></div>
                    <div><?php echo htmlspecialchars($address); ?></div>
                </div>
            </div>

            <div class="pic">
                <img src="<?php echo htmlspecialchars($resume['photo']); ?>" alt="picture">
            </div>
        </div>

        <hr>

        <div class="objective">
            <div>
                <?php echo nl2br(htmlspecialchars($resume['description'])); ?>
            </div>
        </div> 

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
                    <div><?php echo htmlspecialchars($age); ?></div>
                    <div><?php echo htmlspecialchars($sex); ?></div>
                    <div><?php echo htmlspecialchars($bday); ?></div>
                    <div><?php echo htmlspecialchars($birthPlace); ?></div>
                    <div><?php echo htmlspecialchars($status); ?></div>
                    <div><?php echo htmlspecialchars($nationality); ?></div>
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
                    <div><?php echo "<b>" . htmlspecialchars($resume['course_program']) . "</b>"; ?></div>
                    <div><?php echo htmlspecialchars($resume['school']); ?></div>
                    <div>
                        <?php 
                        if ($resume['date_started']) {
                            $start = date('F Y', strtotime($resume['date_started']));
                            $end = $resume['date_ended'] ? date('F Y', strtotime($resume['date_ended'])) : 'Present';
                            echo htmlspecialchars($start . ' - ' . $end);
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="skills">
            <div class="skillsTitle">
                <div><?php echo "SKILLS"; ?></div>
            </div>
            <div class="skillsInfo">
                <ul>
                    <?php if (!empty($resume['skills_web_dev'])): ?>
                        <li><b>Web Development:</b> <?php echo htmlspecialchars($resume['skills_web_dev']); ?></li>
                    <?php endif; ?>
                    
                    <?php if (!empty($resume['skills_ui_ux'])): ?>
                        <li><b>UI/UX Design:</b> <?php echo htmlspecialchars($resume['skills_ui_ux']); ?></li>
                    <?php endif; ?>
                    
                    <?php if (!empty($resume['skills_programming'])): ?>
                        <li><b>Programming:</b> <?php echo htmlspecialchars($resume['skills_programming']); ?></li>
                    <?php endif; ?>
                    
                    <?php if (!empty($resume['skills_soft'])): ?>
                        <li><b>Soft Skills:</b> <?php echo htmlspecialchars($resume['skills_soft']); ?></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
    
</body>
</html>