<?php
require_once 'db.php';

try {
    $stmt = $pdo->query("SELECT user_id, full_name, title, email, photo FROM user_resume ORDER BY full_name ASC");
    $resumes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Error fetching data: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume Directory</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            padding: 20px;
        }
        
        .directory-header {
            text-align: center;
            color: #333;
            padding: 40px 20px;
        }
        
        .directory-header h1 {
            font-size: 40px;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .directory-header p {
            font-size: 18px;
            opacity: 0.95;
        }
        
        .directory-container {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .search-box {
            background: white;
            padding: 10px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            width: 500px;
            margin: 20px auto; 
        }
        
        .search-box input {
            width: 100%;
            padding: 15px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
            color: #1f2937;
        }
        
        .search-box input:focus {
            outline: none;
            border-color: #333;
        }
        
        .resume-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .resume-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .resume-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .resume-photo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
            border: 4px solid #667eea;
        }
        
        .resume-name {
            font-size: 22px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 8px;
        }
        
        .resume-title {
            font-size: 16px;
            color: #6b7280;
            margin-bottom: 8px;
        }
        
        .resume-email {
            font-size: 14px;
            color: #9ca3af;
            margin-bottom: 20px;
            word-break: break-all;
        }
        
        .view-btn {
            display: inline-block;
            padding: 12px 30px;
            background: #4338ca;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: transform 0.2s, box-shadow 0.2s;
            width: 100%;
        }
        
        .view-btn:hover {
            transform: scale(1);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .no-results {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 12px;
            color: #6b7280;
        }
        
        .no-results h2 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .resume-count {
            color: #4f46e5;
            margin-top: 15px;
            font-size: 16px;
        }
        
        .back-home {
            text-align: center;
            margin-top: 40px;
        }
        
        .back-home a {
            display: inline-block;
            padding: 12px 30px;
            background: #4338ca;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            border: 2px solid white;
            transition: all 0.3s;
        }
        
        .back-home a:hover {
            transform: scale(1);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        @media (max-width: 768px) {
            .directory-header h1 {
                font-size: 36px;
            }
            
            .resume-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="directory-header">
        <h1> Public Resume </h1>
        <p>Browse and view resumes from our community</p>
        <div class="resume-count">
            <?php echo count($resumes); ?> Resume<?php echo count($resumes) != 1 ? 's' : ''; ?> Available
        </div>

        <div class="back-home">
            <a href="resume_home.php">← Back to Home </a>
        </div>
    </div>
    
    <div class="directory-container">
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Search by name, title, or email..." onkeyup="filterResumes()">
        </div>
        
        <?php if (empty($resumes)): ?>
            <div class="no-results">
                <h2>No Resumes Found</h2>
                <p>There are currently no resumes in the directory.</p>
            </div>
        <?php else: ?>
            <div class="resume-grid" id="resumeGrid">
                <?php foreach ($resumes as $resume): ?>
                    <div class="resume-card" data-name="<?php echo htmlspecialchars(strtolower($resume['full_name'] ?? '')); ?>" 
                         data-title="<?php echo htmlspecialchars(strtolower($resume['title'] ?? '')); ?>"
                         data-email="<?php echo htmlspecialchars(strtolower($resume['email'] ?? '')); ?>">
                        <img src="<?php echo htmlspecialchars($resume['photo'] ?? 'formalpic.jpg'); ?>" 
                             alt="Profile Photo" 
                             class="resume-photo"
                             onerror="this.src='formalpic.jpg'">
                        <div class="resume-name">
                            <?php echo htmlspecialchars($resume['full_name'] ?? 'Name Not Available'); ?>
                        </div>
                        <div class="resume-title">
                            <?php echo htmlspecialchars($resume['title'] ?? 'Title Not Available'); ?>
                        </div>
                        <div class="resume-email">
                            <?php echo htmlspecialchars($resume['email'] ?? ''); ?>
                        </div>
                        <a href="public_resume.php?id=<?php echo $resume['user_id']; ?>" class="view-btn" target="_blank">
                            View Resume
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="no-results" id="noResults" style="display: none;">
                <h2>No Matching Resumes</h2>
                <p>Try adjusting your search terms.</p>
            </div>
        <?php endif; ?>
        
    </div>
    
    <script>
        function filterResumes() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const resumeCards = document.querySelectorAll('.resume-card');
            const noResults = document.getElementById('noResults');
            let visibleCount = 0;
            
            resumeCards.forEach(card => {
                const name = card.getAttribute('data-name');
                const title = card.getAttribute('data-title');
                const email = card.getAttribute('data-email');
                
                if (name.includes(searchInput) || title.includes(searchInput) || email.includes(searchInput)) {
                    card.style.display = 'flex';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            if (visibleCount === 0 && searchInput !== '') {
                noResults.style.display = 'block';
            } else {
                noResults.style.display = 'none';
            }
        }
    </script>
</body>
</html>