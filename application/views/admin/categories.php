<?php $this->load->view('partials/header', ['title' => 'Categories']); ?>

<div class="page-header">
    <h1 class="page-title">Categories</h1>
    <div class="page-actions">
        <a href="<?php echo base_url('admin/categories/create'); ?>" class="btn btn-primary">
            <i class='bx bx-plus'></i> Add Category
        </a>
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


<div class="data-container">
    <table id="catTable" class="dataTable js-datatable">
        <thead>
            <tr>
                <th class="no-sort" style="width: 40px;"><input type="checkbox"></th>
                <th>Name</th>
                <th class="no-sort" style="width: 100px;"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $cat): ?>
            <tr>
                <td><input type="checkbox"></td>
                <td>
                    <div class="avatar-group">
                        <div class="avatar-initial bg-blue">
                            <i class='bx bx-category'></i>
                        </div>
                        <span class="primary-text"><?php echo $cat->name; ?></span>
                    </div>
                </td>
                <td>
                    <div style="display: flex; gap: 8px;">
                        <a href="<?php echo base_url('admin/categories/edit/' . $cat->id); ?>" class="btn-icon" style="color: var(--text-muted);"><i class='bx bx-edit-alt'></i></a>
                        <a href="<?php echo base_url('admin/categories/delete/' . $cat->id); ?>" class="btn-icon" style="color: #ef4444;" onclick="return confirm('Delete this category?')"><i class='bx bx-trash'></i></a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<?php $this->load->view('partials/footer'); ?>
