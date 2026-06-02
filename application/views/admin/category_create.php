<?php $this->load->view('partials/header', ['title' => 'Add Category']); ?>

<div class="page-header">
    <h1 class="page-title">Add New Category</h1>
    <div class="page-actions">
        <a href="<?php echo base_url('admin/categories'); ?>" class="btn btn-outline">
            <i class='bx bx-arrow-back'></i> Back to Categories
        </a>
    </div>
</div>

<div class="data-container" style="max-width: 600px; padding: 24px;">
    <?php if ($this->session->flashdata('error')): ?>
        <div style="background: #fef2f2; border: 1px solid #ef4444; color: #991b1b; padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1.5rem; font-size: 0.875rem;">
            <?php echo $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>

    <form action="<?php echo base_url('admin/categories/save'); ?>" method="POST" novalidate>
        <div class="form-group">
            <label class="form-label">Category Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        
        <div style="display: flex; gap: 12px; margin-top: 24px;">
            <button type="submit" class="btn btn-primary">Create Category</button>
            <a href="<?php echo base_url('admin/categories'); ?>" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>

<?php $this->load->view('partials/footer'); ?>
