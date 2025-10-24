<?php
require_once 'db.php';

// Get user ID from URL parameter
$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($user_id <= 0) {
    echo "<!DOCTYPE html>";
    echo "<html><head><title>Invalid ID</title>";
    echo "<style>body{font-family:Arial;text-align:center;padding:50px;background:#f3f4f6;}";
    echo ".error-box{background:white;padding:40px;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.1);max-width:500px;margin:0 auto;}";
    echo "h1{color:#dc2626;margin-bottom:20px;}";
    echo "a{display:inline-block;margin-top:20px;padding:10px 20px;background:#4f46e5;color:white;text-decoration:none;border-radius:6px;}</style></head>";
    echo "<body><div class='error-box'>";
    echo "<h1>❌ Invalid Resume ID</h1>";
    echo "<p>Please provide a valid user ID in the URL.</p>";
    echo "<p>Example: <code>public_resume.php?id=1</code></p>";
    echo "<a href='directory.php'>← Back to Directory</a>";
    echo "</div></body></html>";
    exit;
}

// Fetch resume data from database
try {
    $stmt = $pdo->prepare("SELECT * FROM user_resume WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $resume = $stmt->fetch(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    die("Error fetching data: " . $e->getMessage());
}

// Check if resume exists
if (!$resume) {
    echo "<!DOCTYPE html>";
    echo "<html><head><title>Resume Not Found</title>";
    echo "<style>body{font-family:Arial;text-align:center;padding:50px;background:#f3f4f6;}";
    echo ".error-box{background:white;padding:40px;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.1);max-width:500px;margin:0 auto;}";
    echo "h1{color:#dc2626;margin-bottom:20px;}";
    echo "a{display:inline-block;margin-top:20px;padding:10px 20px;background:#4f46e5;color:white;text-decoration:none;border-radius:6px;}</style></head>";
    echo "<body><div class='error-box'>";
    echo "<h1>❌ Resume Not Found</h1>";
    echo "<p>The resume for user ID <strong>" . htmlspecialchars($user_id) . "</strong> does not exist or has not been created yet.</p>";
    echo "<a href='directory.php'>← Back to Directory</a>";
    echo "</div></body></html>";
    exit;
}

$name = $resume['full_name'] ?? 'Name Not Available';
$title = $resume['title'];
$email = $resume['email'] ?? '';
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
    <title><?php echo htmlspecialchars($name); ?> - Resume</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        .public-header {
            color: #333;
            padding: 50px;
            text-align: center;
        }
        
        .public-header h1 {
            margin: 0;
            font-size: 40px;
        }
        
        .public-header p {
            margin: 5px 0 0 0;
            opacity: 0.9;
            font-size: 14px;
        }
                
        .watermark {
            text-align: center;
            padding: 20px;
            color: #6b7280;
            font-size: 13px;
            border-top: 1px solid #e5e7eb;
            margin-top: 30px;
        }
        
        @media print {
            .public-header,
            .share-section,
            .watermark {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="public-header">
        <h1>Public Resume View</h1>
        <p>This is a read-only view of the resume</p>
    </div>

    <div class="resume-container">
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
                <div>PERSONAL DATA</div>
            </div>
            <div class="content">
                <div class="label">
                    <div>Age:</div>
                    <div>Sex:</div>
                    <div>Birthday:</div>
                    <div>Birth Place:</div>
                    <div>Civil Status:</div>
                    <div>Nationality:</div>
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
                <div>EDUCATIONAL BACKGROUND</div>
            </div>

            <div class="college">
                <div class="collegeLabel">
                    <div>College</div>
                </div>
                <div class="collegeInfo">
                    <div><b><?php echo htmlspecialchars($resume['course_program']); ?></b></div>
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
                <div>SKILLS</div>
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

   <!-- <div class="watermark">
        <p>This resume was generated and hosted online.</p>
        <p>Last updated: <?php echo date('F j, Y', strtotime($resume['last_updated'])); ?></p>

    </div>-->

</body>
</html>