<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php'); 
    exit;
}

require_once 'db.php';

$user_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Check if resume exists
        $stmt = $pdo->prepare("SELECT id FROM user_resume WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $existing = $stmt->fetch();
        
        // Handle file upload
        $photo_path = $_POST['current_photo'] ?? 'formalpic.jpg';
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['photo']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if (in_array($ext, $allowed)) {
                $new_filename = 'uploads/profile_' . $user_id . '_' . time() . '.' . $ext;
                
                // Create uploads directory if it doesn't exist
                if (!file_exists('uploads')) {
                    mkdir('uploads', 0777, true);
                }
                
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $new_filename)) {
                    $photo_path = $new_filename;
                }
            }
        }
        
        // Convert date formats
        $birthday = !empty($_POST['birthday']) ? $_POST['birthday'] : null;
        $date_started = !empty($_POST['date_started']) ? $_POST['date_started'] : null;
        $date_ended = !empty($_POST['date_ended']) ? $_POST['date_ended'] : null;
        
        if ($existing) {
            // Update existing resume
            $sql = "UPDATE user_resume SET 
                    title = ?,
                    address = ?,
                    phone = ?,
                    description = ?,
                    age = ?,
                    sex = ?,
                    birthday = ?,
                    birth_place = ?,
                    civil_status = ?,
                    nationality = ?,
                    school = ?,
                    course_program = ?,
                    date_started = ?,
                    date_ended = ?,
                    skills_web_dev = ?,
                    skills_ui_ux = ?,
                    skills_programming = ?,
                    skills_soft = ?,
                    photo = ?
                    WHERE user_id = ?";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $_POST['title'],
                $_POST['address'],
                $_POST['phone'],
                $_POST['description'],
                $_POST['age'],
                $_POST['sex'],
                $birthday,
                $_POST['birth_place'],
                $_POST['civil_status'],
                $_POST['nationality'],
                $_POST['school'],
                $_POST['course_program'],
                $date_started,
                $date_ended,
                $_POST['skills_web_dev'],
                $_POST['skills_ui_ux'],
                $_POST['skills_programming'],
                $_POST['skills_soft'],
                $photo_path,
                $user_id
            ]);
        } else {
            // Insert new resume
            $sql = "INSERT INTO user_resume (
                    user_id, title, address, phone, description, age, sex, birthday,
                    birth_place, civil_status, nationality, school, course_program,
                    date_started, date_ended, skills_web_dev, skills_ui_ux,
                    skills_programming, skills_soft, photo
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $user_id,
                $_POST['title'],
                $_POST['address'],
                $_POST['phone'],
                $_POST['description'],
                $_POST['age'],
                $_POST['sex'],
                $birthday,
                $_POST['birth_place'],
                $_POST['civil_status'],
                $_POST['nationality'],
                $_POST['school'],
                $_POST['course_program'],
                $date_started,
                $date_ended,
                $_POST['skills_web_dev'],
                $_POST['skills_ui_ux'],
                $_POST['skills_programming'],
                $_POST['skills_soft'],
                $photo_path
            ]);
        }
        
        header('Location: resume.php');
        exit;
        
    } catch(PDOException $e) {
        $error = "Error saving resume: " . $e->getMessage();
    }
}

// Fetch existing resume data
try {
    $stmt = $pdo->prepare("SELECT * FROM user_resume WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $resume = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Get user info
    $stmt = $pdo->prepare("SELECT first_name, last_name, email FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    die("Error fetching data: " . $e->getMessage());
}

// Set defaults if no resume exists
if (!$resume) {
    $resume = [
        'title' => 'Computer Science',
        'address' => '',
        'phone' => '',
        'description' => '',
        'age' => '',
        'sex' => 'Male',
        'birthday' => '',
        'birth_place' => '',
        'civil_status' => 'Single',
        'nationality' => 'Filipino',
        'school' => '',
        'course_program' => '',
        'date_started' => '',
        'date_ended' => '',
        'skills_web_dev' => '',
        'skills_ui_ux' => '',
        'skills_programming' => '',
        'skills_soft' => '',
        'photo' => 'formalpic.jpg'
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Resume</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        .edit-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 30px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .edit-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .edit-header h1 {
            margin: 0;
            color: #333;
        }
        
        .back-btn {
            display: inline-block;
            padding: 8px 14px;
            background: #4f46e5;
            color: #fff;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
        }
        
        .back-btn:hover {
            background: #4b5563;
        }
        
        .form-section {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .form-section:last-child {
            border-bottom: none;
        }
        
        .form-section h2 {
            color: #4f46e5;
            margin-bottom: 15px;
            font-size: 18px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #374151;
            font-weight: 500;
        }
        
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="tel"],
        .form-group input[type="date"],
        .form-group input[type="number"],
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            color: #333;
            box-sizing: border-box;
        }
        
        .form-group textarea {
            min-height: 100px;
            resize: vertical;
            font-family: inherit;
        }
        
        .form-group input[type="file"] {
            padding: 8px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .submit-btn {
            width: 100%;
            padding: 12px;
            background: #4f46e5;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 20px;
        }
        
        .submit-btn:hover {
            background: #4338ca;
        }
        
        .current-image {
            max-width: 150px;
            margin-top: 10px;
            border-radius: 8px;
        }
        
        .error {
            background: #fee2e2;
            color: #dc2626;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        
        .hint {
            font-size: 12px;
            color: #6b7280;
            margin-top: 3px;
        }
    </style>
</head>
<body>
    <div class="edit-container">
        <div class="edit-header">
            <h1>Edit Resume</h1>
            <a href="resume.php" class="back-btn">← Back to Resume</a>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="current_photo" value="<?php echo htmlspecialchars($resume['photo']); ?>">
            
            <!-- Personal Information -->
            <div class="form-section">
                <h2>Personal Information</h2>
                <div class="form-group">
                    <label> First Name</label>
                    <input type="text" value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" disabled>
                    <div class="hint">Name cannot be changed here. Contact administrator.</div>
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" disabled>
                    <div class="hint">Name cannot be changed here. Contact administrator.</div>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" disabled>
                    <div class="hint">Email cannot be changed here. Contact administrator.</div>
                </div>
                <div class="form-group">
                    <label>Title/Field *</label>
                    <input type="text" name="title" value="<?php echo htmlspecialchars($resume['title']); ?>" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Phone *</label>
                        <input type="tel" name="phone" value="<?php echo htmlspecialchars($resume['phone']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Address *</label>
                        <input type="text" name="address" value="<?php echo htmlspecialchars($resume['address']); ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Profile Picture</label>
                    <input type="file" name="photo" accept="image/*">
                    <?php if (!empty($resume['photo']) && file_exists($resume['photo'])): ?>
                        <img src="<?php echo htmlspecialchars($resume['photo']); ?>" alt="Current Profile" class="current-image">
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Objective -->
            <div class="form-section">
                <h2>Objective / Career Summary</h2>
                <div class="form-group">
                    <label>Description *</label>
                    <textarea name="description" required><?php echo htmlspecialchars($resume['description']); ?></textarea>
                </div>
            </div>
            
            <!-- Personal Data -->
            <div class="form-section">
                <h2>Personal Data</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label>Age *</label>
                        <input type="number" name="age" value="<?php echo htmlspecialchars($resume['age']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Sex *</label>
                        <select name="sex" required>
                            <option value="Male" <?php echo $resume['sex'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo $resume['sex'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Birthday *</label>
                        <input type="date" name="birthday" value="<?php echo htmlspecialchars($resume['birthday']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Birth Place *</label>
                        <input type="text" name="birth_place" value="<?php echo htmlspecialchars($resume['birth_place']); ?>" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Civil Status *</label>
                        <select name="civil_status" required>
                            <option value="Single" <?php echo $resume['civil_status'] === 'Single' ? 'selected' : ''; ?>>Single</option>
                            <option value="Married" <?php echo $resume['civil_status'] === 'Married' ? 'selected' : ''; ?>>Married</option>
                            <option value="Divorced" <?php echo $resume['civil_status'] === 'Divorced' ? 'selected' : ''; ?>>Divorced</option>
                            <option value="Widowed" <?php echo $resume['civil_status'] === 'Widowed' ? 'selected' : ''; ?>>Widowed</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nationality *</label>
                        <input type="text" name="nationality" value="<?php echo htmlspecialchars($resume['nationality']); ?>" required>
                    </div>
                </div>
            </div>
            
            <!-- Education -->
            <div class="form-section">
                <h2>Educational Background</h2>
                <div class="form-group">
                    <label>Course/Program *</label>
                    <input type="text" name="course_program" value="<?php echo htmlspecialchars($resume['course_program']); ?>" required>
                </div>
                <div class="form-group">
                    <label>School/University *</label>
                    <input type="text" name="school" value="<?php echo htmlspecialchars($resume['school']); ?>" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Date Started *</label>
                        <input type="date" name="date_started" value="<?php echo htmlspecialchars($resume['date_started']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Date Ended</label>
                        <input type="date" name="date_ended" value="<?php echo htmlspecialchars($resume['date_ended']); ?>">
                        <div class="hint">Leave empty if currently studying</div>
                    </div>
                </div>
            </div>
            
            <!-- Skills -->
            <div class="form-section">
                <h2>Skills</h2>
                <div class="form-group">
                    <label>Web Development</label>
                    <input type="text" name="skills_web_dev" value="<?php echo htmlspecialchars($resume['skills_web_dev']); ?>" placeholder="e.g., HTML, CSS, JavaScript, PHP">
                </div>
                <div class="form-group">
                    <label>UI/UX Design</label>
                    <input type="text" name="skills_ui_ux" value="<?php echo htmlspecialchars($resume['skills_ui_ux']); ?>" placeholder="e.g., Figma, Adobe XD, Wireframing">
                </div>
                <div class="form-group">
                    <label>Programming Languages</label>
                    <input type="text" name="skills_programming" value="<?php echo htmlspecialchars($resume['skills_programming']); ?>" placeholder="e.g., Python, Java, C++, C#">
                </div>
                <div class="form-group">
                    <label>Soft Skills</label>
                    <input type="text" name="skills_soft" value="<?php echo htmlspecialchars($resume['skills_soft']); ?>" placeholder="e.g., Communication, Teamwork, Time Management">
                </div>
            </div>
            
            <button type="submit" class="submit-btn">Save Changes</button>
        </form>
    </div>
</body>
</html>