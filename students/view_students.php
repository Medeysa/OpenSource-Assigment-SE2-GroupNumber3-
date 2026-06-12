<?php
require_once '../config/database.php';
require_once '../includes/session.php';

require_login();

// Fetch all student records in newest-first order.
$statement = $pdo->prepare(
    'SELECT id, registration_number, first_name, last_name, gender, school_level, created_at
     FROM students
     ORDER BY created_at DESC'
);
$statement->execute();
$students = $statement->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students - Student Information Management System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <h1>Student Records</h1>
            <p>View all registered students in the system.</p>
        </div>
    </header>

    <main class="container">
        <section class="table-section">
            <div class="page-actions">
                <a href="add_student.php">Register New Student</a>
                <a href="../auth/logout.php">Logout</a>
            </div>

            <?php if (count($students) === 0): ?>
                <p>No student records found.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Registration Number</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Gender</th>
                            <th>School Level</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($student['registration_number']); ?></td>
                                <td><?php echo htmlspecialchars($student['first_name']); ?></td>
                                <td><?php echo htmlspecialchars($student['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($student['gender']); ?></td>
                                <td><?php echo htmlspecialchars($student['school_level']); ?></td>
                                <td><?php echo htmlspecialchars($student['created_at']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
