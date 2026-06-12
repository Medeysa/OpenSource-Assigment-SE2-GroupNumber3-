<?php
require_once '../config/database.php';
require_once '../includes/session.php';

// Secure the page - require user to be logged in
require_login();

$errors = [];
$success = '';

// Check if a project ID is provided in the query string
$project_id = $_GET['id'] ?? null;

if (!$project_id) {
    header('Location: view_projects.php');
    exit;
}

// Fetch the existing project details from the database
$statement = $pdo->prepare('SELECT * FROM projects WHERE id = ? LIMIT 1');
$statement->execute([$project_id]);
$project = $statement->fetch();

// If the project doesn't exist, redirect to the projects listing page
if (!$project) {
    header('Location: view_projects.php');
    exit;
}

// Pre-fill form fields with database values initially
$project_name = $project['project_name'];
$description = $project['description'];
$status = $project['status'];
$start_date = $project['start_date'];
$end_date = $project['end_date'] ?? '';

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_name = trim($_POST['project_name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = $_POST['status'] ?? '';
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';

    // Validate inputs
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

    // Check if the new project name already exists on another project (excluding current one)
    if (empty($errors)) {
        $check_statement = $pdo->prepare('SELECT id FROM projects WHERE project_name = ? AND id != ? LIMIT 1');
        $check_statement->execute([$project_name, $project_id]);

        if ($check_statement->fetch()) {
            $errors[] = 'A project with this name already exists.';
        }
    }

    // If no errors, update the database record
    if (empty($errors)) {
        $update_statement = $pdo->prepare(
            'UPDATE projects 
             SET project_name = ?, description = ?, status = ?, start_date = ?, end_date = ? 
             WHERE id = ?'
        );

        $update_statement->execute([
            $project_name,
            $description,
            $status,
            $start_date,
            $end_date !== '' ? $end_date : null,
            $project_id
        ]);

        $success = 'Project updated successfully.';
        
        // Refresh the database values in case we need to render the form with updated data
        $statement = $pdo->prepare('SELECT * FROM projects WHERE id = ? LIMIT 1');
        $statement->execute([$project_id]);
        $project = $statement->fetch();
    }
}

$page_title = 'Edit Project';
$page_description = 'Update software project details in the tracking system.';
$base_path = '..';
$active_page = 'view'; // Mark the Status navigation page active since this is editing an existing item

require_once '../includes/header.php';
?>

<main class="page-shell">
    <section class="content-panel">
        <div class="section-toolbar">
            <div>
                <span class="eyebrow">Modify</span>
                <h2>Update Project Information</h2>
            </div>
            <a class="button button-secondary" href="view_projects.php">Back to Projects</a>
        </div>

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

        <form action="edit_project.php?id=<?php echo htmlspecialchars($project_id); ?>" method="POST" class="record-form">
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
                <button class="button button-primary" type="submit">Update Project</button>
                <a class="button button-secondary" href="view_projects.php">Cancel</a>
            </div>
        </form>
    </section>
</main>

<?php require_once '../includes/footer.php'; ?>
