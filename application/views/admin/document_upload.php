<?php $this->load->view('partials/header', ['title' => 'Upload Document']); ?>

<div class="page-header">
    <h1 class="page-title">Upload New Document</h1>
    <div class="page-actions">
        <a href="<?php echo base_url('admin/documents'); ?>" class="btn btn-outline">
            <i class='bx bx-arrow-back'></i> Back to Documents
        </a>
    </div>
</div>

<div class="data-container" style="max-width: 600px; padding: 24px;">
    <?php if ($this->session->flashdata('error')): ?>
        <div style="background: #fef2f2; border: 1px solid #ef4444; color: #991b1b; padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1.5rem; font-size: 0.875rem;">
            <?php echo $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>

    <form action="<?php echo base_url('admin/documents/save'); ?>" method="POST" enctype="multipart/form-data" novalidate>
        <div class="form-group">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label class="form-label">Category</label>
            <select name="category_id" class="form-control">
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat->id; ?>"><?php echo $cat->name; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Document File <span style="font-weight: normal; color: var(--text-muted);">(pdf, doc, docx, zip)</span></label>
            <input type="file" name="document" class="form-control" style="padding: 10px;" required>
        </div>
        
        <div style="display: flex; gap: 12px; margin-top: 24px;">
            <button type="submit" class="btn btn-primary">Upload Document</button>
            <a href="<?php echo base_url('admin/documents'); ?>" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>

<?php $this->load->view('partials/footer'); ?>
