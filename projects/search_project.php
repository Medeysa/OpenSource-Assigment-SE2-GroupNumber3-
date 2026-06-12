<?php
require_once '../config/database.php';
require_once '../includes/session.php';

require_login();

$project_name = '';
$projects = [];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_name = trim($_POST['project_name'] ?? '');

    if ($project_name === '') {
        $message = 'Please enter a project name.';
    } else {
        // Prepared statements protect the search from SQL injection.
        $statement = $pdo->prepare(
            'SELECT id, project_name, description, status, start_date, end_date, created_at
             FROM projects
             WHERE project_name LIKE ?
             ORDER BY created_at DESC'
        );
        $statement->execute(['%' . $project_name . '%']);
        $projects = $statement->fetchAll();

        if (count($projects) === 0) {
            $message = 'No project found with that name.';
        }
    }
}

$page_title = 'Search Projects';
$page_description = 'Search software projects by project name.';
$base_path = '..';
$active_page = 'search';

require_once '../includes/header.php';
?>

<main class="page-shell">
    <section class="content-panel search-layout">
        <form action="search_project.php" method="POST" class="search-form">
            <div class="form-group">
                <label for="project_name">Project Name</label>
                <input
                    type="text"
                    id="project_name"
                    name="project_name"
                    value="<?php echo htmlspecialchars($project_name); ?>"
                    placeholder="Enter project name"
                    required
                >
            </div>

            <button class="button button-primary" type="submit">Search Project</button>
        </form>

        <?php if ($message !== ''): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php foreach ($projects as $project): ?>
            <div class="result-card">
                <div class="result-heading">
                    <span class="eyebrow">Project Found</span>
                    <h2><?php echo htmlspecialchars($project['project_name']); ?></h2>
                    <p><?php echo htmlspecialchars($project['description']); ?></p>
                </div>

                <dl class="details-grid">
                    <div>
                        <dt>Status</dt>
                        <dd><?php echo htmlspecialchars($project['status']); ?></dd>
                    </div>
                    <div>
                        <dt>Start Date</dt>
                        <dd><?php echo htmlspecialchars($project['start_date']); ?></dd>
                    </div>
                    <div>
                        <dt>End Date</dt>
                        <dd><?php echo htmlspecialchars($project['end_date'] ?? 'Not set'); ?></dd>
                    </div>
                </dl>
            </div>
        <?php endforeach; ?>
    </section>
</main>

<?php require_once '../includes/footer.php'; ?>
