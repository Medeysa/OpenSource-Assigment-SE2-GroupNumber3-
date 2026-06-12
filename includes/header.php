<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $base_path; ?>/assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <header class="app-header">
        <nav class="navbar">
            <a class="brand" href="<?php echo $base_path; ?>/index.php">
                <span class="brand-mark">SPTS</span>
                <span>Project Tracker</span>
            </a>

            <button class="nav-toggle" type="button" aria-label="Open navigation" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <div class="nav-links">
                <a class="<?php echo $active_page === 'home' ? 'active' : ''; ?>" href="<?php echo $base_path; ?>/index.php">Home</a>
                <a class="<?php echo $active_page === 'add' ? 'active' : ''; ?>" href="<?php echo $base_path; ?>/projects/add_project.php">Record Project</a>
                <a class="<?php echo $active_page === 'view' ? 'active' : ''; ?>" href="<?php echo $base_path; ?>/projects/view_projects.php">Status</a>
                <a class="<?php echo $active_page === 'search' ? 'active' : ''; ?>" href="<?php echo $base_path; ?>/projects/search_project.php">Search</a>
                <?php if (is_logged_in()): ?>
                    <a class="nav-logout" href="<?php echo $base_path; ?>/auth/logout.php">Logout</a>
                <?php else: ?>
                    <a class="<?php echo $active_page === 'login' ? 'active' : ''; ?>" href="<?php echo $base_path; ?>/auth/login.php">Login</a>
                <?php endif; ?>
                <button id="theme-toggle" class="theme-toggle" type="button" aria-label="Toggle theme">
                    <span class="theme-toggle-icon">🌙</span>
                </button>
            </div>
        </nav>
    </header>

    <?php if (!empty($page_description)): ?>
        <section class="page-header">
            <div>
                <h1><?php echo htmlspecialchars($page_title); ?></h1>
                <p><?php echo htmlspecialchars($page_description); ?></p>
            </div>
        </section>
    <?php endif; ?>
