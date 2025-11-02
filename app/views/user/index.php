<div class="main-wrapper main-wrapper-1">

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
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="table-1">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th>Name</th>
                                                <th>Username</th>
                                                <th>Role</th>
                                                <?php if (!empty($session['role']) && $session['role'] === 'admin'): ?>
                                                    <th>Action</th>
                                                <?php endif ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($data['users'] as $index => $admin): ?>
                                                <tr>
                                                    <td class="text-center"><?= $index + 1 ?></td>
                                                    <td><?= htmlspecialchars($admin['name']) ?></td>
                                                    <td><?= htmlspecialchars($admin['username']) ?></td>
                                                    <td><?= htmlspecialchars($admin['role']) ?></td>
                                                    <?php if (!empty($session['role']) && $session['role'] === 'admin'): ?>
                                                        <td>
                                                            <a href="<?= BASEURL ?>UsersController/delete/<?= $admin['id_user'] ?>"
                                                                class="btn btn-danger btn-sm"
                                                                onclick="return confirm('Are you sure you want to delete this admin?');">
                                                                Delete
                                                            </a>
                                                        </td>
                                                    <?php endif ?>
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

</div>