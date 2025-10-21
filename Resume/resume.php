<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php'); 
    exit;
}

require_once 'db.php';

$user_id = $_SESSION['user_id'];

// Fetch resume data from database
try {
    $stmt = $pdo->prepare("SELECT * FROM user_resume WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $resume = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Get user's name and email from users table
    $stmt = $pdo->prepare("SELECT first_name, last_name, email FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    die("Error fetching data: " . $e->getMessage());
}

// Set default values if no resume exists yet
if (!$resume) {
    $resume = [
        'title' => 'Computer Science',
        'address' => 'Your Address',
        'phone' => 'Your Phone',
        'description' => 'Please edit your resume to add your career objective.',
        'age' => '20',
        'sex' => 'Male',
        'birthday' => '2004-10-01',
        'birth_place' => 'Your Birth Place',
        'civil_status' => 'Single',
        'nationality' => 'Filipino',
        'school' => 'Your University',
        'course_program' => 'Bachelor of Science in Computer Science',
        'date_started' => date('Y-m-d'),
        'date_ended' => '',
        'skills_web_dev' => 'HTML, CSS, PHP, Javascript',
        'skills_ui_ux' => 'Figma (Wireframing, Prototyping, Interface Design)',
        'skills_programming' => 'Python, Java, C++, C#',
        'skills_soft' => 'Communication, Time Management, Adaptability, Teamwork & Collaboration',
        'photo' => 'formalpic.jpg'
    ];
}

// Build full name
$name = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
if (empty($name)) {
    $name = 'Your Name';
}

// Assign variables for display
$title = $resume['title'];
$email = $user['email'] ?? 'your@email.com';
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

     <div class="logout">
        <a href="edit_resume.php">Edit Resume</a>
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