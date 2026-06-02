<?php $this->load->view('partials/header', ['title' => 'Permissions']); ?>

<div class="page-header">
    <h1 class="page-title">User Permissions</h1>
    <div class="page-actions">
    </div>
</div>

<?php if ($this->session->flashdata('success')): ?>
    <div style="background: #ecfdf5; border: 1px solid #10b981; color: #065f46; padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1.5rem; font-size: 0.875rem;">
        <?php echo $this->session->flashdata('success'); ?>
    </div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <div style="background: #fef2f2; border: 1px solid #ef4444; color: #991b1b; padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1.5rem; font-size: 0.875rem;">
        <?php echo $this->session->flashdata('error'); ?>
    </div>
<?php endif; ?>

<div class="toolbar">
    <div class="filters">
    </div>
    <div class="search-box">
        <i class='bx bx-search'></i>
        <input type="text" id="customSearchInput" placeholder="Search permissions...">
    </div>
</div>

<div class="data-container">
    <table id="permTable" class="dataTable js-datatable">
        <thead>
            <tr>
                <th class="no-sort" style="width: 40px;"><input type="checkbox"></th>
                <th>User</th>
                <th>Upload</th>
                <th>Edit</th>
                <th>Delete</th>
                <th>Audit</th>
                <th class="no-sort" style="width: 80px;"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($permissions as $p): ?>
            <tr>
                <td><input type="checkbox"></td>
                <td>
                    <div class="avatar-group">
                        <?php 
                            $bg_colors = ['bg-purple', 'bg-blue', 'bg-green', 'bg-red', 'bg-yellow', 'bg-pink'];
                            $color_class = $bg_colors[crc32($p->username) % count($bg_colors)];
                        ?>
                        <div class="avatar-initial <?php echo $color_class; ?>">
                            <?php echo strtoupper(substr($p->username, 0, 2)); ?>
                        </div>
                        <div>
                            <span class="primary-text" style="display:block;"><?php echo $p->username; ?></span>
                            <span style="font-size: 0.75rem; color: var(--text-muted);"><?php echo ucfirst($p->role); ?></span>
                        </div>
                    </div>
                </td>
                <td>
                    <?php if($p->can_upload): ?>
                        <span class="status-active"><i class='bx bx-check'></i> Yes</span>
                    <?php else: ?>
                        <span class="status-nonactive"><i class='bx bx-x'></i> No</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if($p->can_edit): ?>
                        <span class="status-active"><i class='bx bx-check'></i> Yes</span>
                    <?php else: ?>
                        <span class="status-nonactive"><i class='bx bx-x'></i> No</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if($p->can_delete): ?>
                        <span class="status-active"><i class='bx bx-check'></i> Yes</span>
                    <?php else: ?>
                        <span class="status-nonactive"><i class='bx bx-x'></i> No</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if($p->can_view_audit): ?>
                        <span class="status-active"><i class='bx bx-check'></i> Yes</span>
                    <?php else: ?>
                        <span class="status-nonactive"><i class='bx bx-x'></i> No</span>
                    <?php endif; ?>
                </td>
                <td>
                    <div style="display: flex; gap: 8px;">
                        <?php if ($p->user_id != $this->session->userdata('id')): ?>
                            <a href="<?php echo base_url('admin/permissions/edit/' . $p->user_id); ?>" class="btn-icon" style="color: var(--text-muted);"><i class='bx bx-edit-alt'></i></a>
                        <?php else: ?>
                            <span style="color: #666; font-size: 0.75rem;">(Self)</span>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var table = $('#permTable').DataTable();
        $('#customSearchInput').on('keyup', function() {
            table.search(this.value).draw();
        });
    });
</script>

<?php $this->load->view('partials/footer'); ?>
