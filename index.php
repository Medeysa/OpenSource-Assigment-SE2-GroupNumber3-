<?php
require_once 'includes/session.php';

$page_title = 'Software Project Tracking System';
$page_description = '';
$base_path = '.';
$active_page = 'home';

require_once 'includes/header.php';
?>

<main class="page-shell">
    <section class="hero-section">
        <div class="hero-content">
            <span class="eyebrow">CP222 Open Source Assignment</span>
            <h1>Software Project Tracking System</h1>
            <p>
                Record software project information, monitor project status, and search projects by name
                through a clean PHP and MySQL web application.
            </p>
            <div class="hero-actions">
                <?php if (is_logged_in()): ?>
                    <a class="button button-primary" href="projects/add_project.php">Record Project</a>
                    <a class="button button-secondary" href="projects/view_projects.php">View Status</a>
                <?php else: ?>
                    <a class="button button-primary" href="auth/login.php">Login to System</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="hero-panel" aria-label="System summary">
            <div>
                <strong>Record</strong>
                <span>Save project details</span>
            </div>
            <div>
                <strong>Status</strong>
                <span>Track project progress</span>
            </div>
            <div>
                <strong>Search</strong>
                <span>Find projects by name</span>
            </div>
        </div>
    </section>

    <section class="summary-grid">
        <article class="summary-card">
            <span class="card-number">01</span>
            <h2>User Access</h2>
            <p>Login, logout, and session management protect the project tracking pages.</p>
        </article>

        <article class="summary-card">
            <span class="card-number">02</span>
            <h2>Project Information</h2>
            <p>Record project names, descriptions, dates, and current progress status.</p>
        </article>

        <article class="summary-card">
            <span class="card-number">03</span>
            <h2>Project Search</h2>
            <p>Find software projects quickly using the project name.</p>
        </article>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>
