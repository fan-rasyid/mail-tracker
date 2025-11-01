<div class="main-wrapper main-wrapper-1">
    <?php
    require_once __DIR__ . "/../layouts/header.php";
    require_once __DIR__ . "/../layouts/sidebar.php";

    ?>

    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Admin List</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active">
                        <a href="<?= BASEURL ?>dashboard">Dashboard</a>
                    </div>
                    <div class="breadcrumb-item">Admin Management</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Admin Management</h2>
                <p class="section-lead">
                    Below is a list of all administrators. You can add, edit, or delete admin accounts.
                </p>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4>Admin Table</h4>
                                <a href="<?= BASEURL ?>AdminController/create" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Add New Admin
                                </a>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="table-admin">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>Name</th>
                                                <th>Username</th>
                                                <th>Role</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($data['users'] as $index => $admin): ?>
                                                <tr>
                                                    <td class="text-center"><?= $index + 1 ?></td>
                                                    <td><?= htmlspecialchars($admin['name']) ?></td>
                                                    <td><?= htmlspecialchars($admin['username']) ?></td>
                                                    <td><?= htmlspecialchars($admin['role']) ?></td>
                                                    <td>
                                                        <a href="<?= BASEURL ?>AdminController/edit/<?= $admin['id_user'] ?>" 
                                                           class="btn btn-warning btn-sm">
                                                            Edit
                                                        </a>
                                                        <a href="<?= BASEURL ?>AdminController/delete/<?= $admin['id_user'] ?>" 
                                                           class="btn btn-danger btn-sm" 
                                                           onclick="return confirm('Are you sure you want to delete this admin?');">
                                                            Delete
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php require_once __DIR__ . "/../layouts/footer.php"; ?>
</div>
