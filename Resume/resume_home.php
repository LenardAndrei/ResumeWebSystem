<?php
session_start(); 

require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("SELECT first_name, last_name FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Error fetching user: " . $e->getMessage());
}

$name = $user ? $user['first_name'] . ' ' . $user['last_name'] : 'User';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume Home</title>
    <link rel="stylesheet" type="text/css" href="styles.css">

    <style>
        .home_header {
            display: flex;                
            justify-content: space-between; 
            align-items: center;          
            padding: 10px 50px;            
            border-bottom: 2px solid #ddd;  
        }

        .home_header h1 {
            margin-left: 80px;             
            font-size: 32px;
            color: #4f46e5;          
        }

        .user-menu {
            position: relative;
            display: inline-block;
        }

        .user-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 15px;
            background: #f3f4f6;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
            border: none;
            font-size: 15px;
            color: #333;
        }

        .user-toggle:hover {
            background: #e5e7eb;
        }

        .user-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #4f46e5;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
        }

        .dropdown-arrow {
            font-size: 12px;
            transition: transform 0.3s;
        }

        .user-menu.active .dropdown-arrow {
            transform: rotate(180deg);
        }

        .dropdown-menu {
            position: absolute;
            top: 110%;
            right: 0;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .user-menu.active .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-header {
            padding: 15px;
            border-bottom: 1px solid #e5e7eb;
        }

        .dropdown-header .user-name {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 3px;
        }

        .dropdown-header .user-email {
            font-size: 12px;
            color: #6b7280;
        }

        .dropdown-item {
            display: block;
            padding: 12px 15px;
            color: #374151;
            text-decoration: none;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .dropdown-item:hover {
            background: #f3f4f6;
        }

        .dropdown-item.logout {
            color: #dc2626;
            border-top: 1px solid #e5e7eb;
        }

        .dropdown-item.logout:hover {
            background: #fee2e2;
        }

        .resume_button {
            display: flex;            
            justify-content: center;   
            align-items: center;       
            flex-direction: column;     
            height: 80vh;              
        }

        .button {
            text-align: center;
            margin: 10px 10px;
        }
        
        .button a {
            display: inline-block;
            padding: 18px 22px;
            background: #4f46e5;
            color: #fff;
            border-radius: 6px;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s;
        }
        
        .button a:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
    </style>

</head>
    <body>

        <div class="home_header">
            <h1>Resume</h1>
            
            <div class="user-menu" id="userMenu">
                <button class="user-toggle" onclick="toggleDropdown()">
                    <div class="user-icon">
                        <?php echo strtoupper(substr($name, 0, 1)); ?>
                    </div>
                    <span><?php echo htmlspecialchars($name); ?></span>
                    <span class="dropdown-arrow">▼</span>
                </button>
                
                <div class="dropdown-menu">
                    <div class="dropdown-header">
                        <div class="user-name"><?php echo htmlspecialchars($name); ?></div>
                        <div class="user-email">User ID: <?php echo htmlspecialchars($user_id); ?></div>
                    </div>
                    <a href="logout.php" class="dropdown-item logout"> Logout </a>
                </div>
            </div>
        </div>

        <div class="resume_button">
            <div class="button">
                <a href="resume.php">View Resume</a>
            </div>

            <div class="button">
                <a href="edit_resume.php">Edit Resume</a>
            </div>

            <div class="button">
                <a href="directory.php">View Public Resume</a>
            </div>
        </div>

        <script>
            function toggleDropdown() {
                const userMenu = document.getElementById('userMenu');
                userMenu.classList.toggle('active');
            }

            document.addEventListener('click', function(event) {
                const userMenu = document.getElementById('userMenu');
                if (!userMenu.contains(event.target)) {
                    userMenu.classList.remove('active');
                }
            });

            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    document.getElementById('userMenu').classList.remove('active');
                }
            });
        </script>

    </body>
</html>