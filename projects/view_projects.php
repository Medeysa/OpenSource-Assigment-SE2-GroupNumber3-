<?php
require_once '../config/database.php';
require_once '../includes/session.php';

require_login();

// Fetch all project records in newest-first order.
$statement = $pdo->prepare(
    'SELECT id, project_name, description, status, start_date, end_date, created_at
     FROM projects
     ORDER BY created_at DESC'
);
$statement->execute();
$projects = $statement->fetchAll();

$page_title = 'Project Status';
$page_description = 'Display software projects and monitor their current status.';
$base_path = '..';
$active_page = 'view';

require_once '../includes/header.php';
?>

<main class="page-shell">
    <section class="content-panel">
        <div class="section-toolbar">
            <div>
                <span class="eyebrow">Tracking</span>
                <h2>Software Projects</h2>
            </div>
            <a class="button button-primary" href="add_project.php">Record Project</a>
        </div>

        <?php if (count($projects) === 0): ?>
            <div class="empty-state">
                <h3>No project records found</h3>
                <p>Record the first software project to start tracking progress and status.</p>
                <a class="button button-secondary" href="add_project.php">Add Project</a>
            </div>
        <?php else: ?>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Project Name</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($projects as $project): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($project['project_name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($project['description']); ?></td>
                                <td><span class="status-pill"><?php echo htmlspecialchars($project['status']); ?></span></td>
                                <td><?php echo htmlspecialchars($project['start_date']); ?></td>
                                <td><?php echo htmlspecialchars($project['end_date'] ?? 'Not set'); ?></td>
                                <td><?php echo htmlspecialchars($project['created_at']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php require_once '../includes/footer.php'; ?>
