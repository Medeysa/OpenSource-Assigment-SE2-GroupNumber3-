<?php
require_once '../config/database.php';
require_once '../includes/session.php';

require_login();

$errors = [];
$success = '';

$project_name = '';
$description = '';
$status = '';
$start_date = '';
$end_date = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_name = trim($_POST['project_name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = $_POST['status'] ?? '';
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';

    if ($project_name === '') {
        $errors[] = 'Project name is required.';
    }

    if ($description === '') {
        $errors[] = 'Project description is required.';
    }

    if (!in_array($status, ['Planning', 'In Progress', 'Completed', 'On Hold'], true)) {
        $errors[] = 'Please select a valid project status.';
    }

    if ($start_date === '') {
        $errors[] = 'Start date is required.';
    }

    if ($end_date !== '' && $start_date !== '' && $end_date < $start_date) {
        $errors[] = 'End date cannot be earlier than the start date.';
    }

    if (empty($errors)) {
        $check_statement = $pdo->prepare('SELECT id FROM projects WHERE project_name = ? LIMIT 1');
        $check_statement->execute([$project_name]);

        if ($check_statement->fetch()) {
            $errors[] = 'A project with this name already exists.';
        }
    }

    if (empty($errors)) {
        // Prepared statements protect the project record insert from SQL injection.
        $insert_statement = $pdo->prepare(
            'INSERT INTO projects (project_name, description, status, start_date, end_date)
             VALUES (?, ?, ?, ?, ?)'
        );

        $insert_statement->execute([
            $project_name,
            $description,
            $status,
            $start_date,
            $end_date !== '' ? $end_date : null
        ]);

        $success = 'Project recorded successfully.';
        $project_name = '';
        $description = '';
        $status = '';
        $start_date = '';
        $end_date = '';
    }
}

$page_title = 'Record Project';
$page_description = 'Add software project information to the tracking system.';
$base_path = '..';
$active_page = 'add';

require_once '../includes/header.php';
?>

<main class="page-shell">
    <section class="content-panel">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($success !== ''): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form action="add_project.php" method="POST" class="record-form">
            <div class="form-group">
                <label for="project_name">Project Name</label>
                <input
                    type="text"
                    id="project_name"
                    name="project_name"
                    value="<?php echo htmlspecialchars($project_name); ?>"
                    placeholder="Example: Library Management System"
                    required
                >
            </div>

            <div class="form-group">
                <label for="status">Project Status</label>
                <select id="status" name="status" required>
                    <option value="">Select status</option>
                    <option value="Planning" <?php echo $status === 'Planning' ? 'selected' : ''; ?>>Planning</option>
                    <option value="In Progress" <?php echo $status === 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                    <option value="Completed" <?php echo $status === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                    <option value="On Hold" <?php echo $status === 'On Hold' ? 'selected' : ''; ?>>On Hold</option>
                </select>
            </div>

            <div class="form-group">
                <label for="start_date">Start Date</label>
                <input
                    type="date"
                    id="start_date"
                    name="start_date"
                    value="<?php echo htmlspecialchars($start_date); ?>"
                    data-start-date
                    required
                >
            </div>

            <div class="form-group">
                <label for="end_date">End Date</label>
                <input
                    type="date"
                    id="end_date"
                    name="end_date"
                    value="<?php echo htmlspecialchars($end_date); ?>"
                    min="<?php echo htmlspecialchars($start_date); ?>"
                    data-end-date
                >
            </div>

            <div class="form-group full-span">
                <label for="description">Project Description</label>
                <textarea
                    id="description"
                    name="description"
                    rows="5"
                    required
                ><?php echo htmlspecialchars($description); ?></textarea>
            </div>

            <div class="form-actions">
                <button class="button button-primary" type="submit">Save Project</button>
                <a class="button button-secondary" href="view_projects.php">View Projects</a>
            </div>
        </form>
    </section>
</main>

<?php require_once '../includes/footer.php'; ?>
