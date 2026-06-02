<?php $this->load->view('partials/header', ['title' => 'Edit Document']); ?>

<div class="page-header">
    <h1 class="page-title">Edit Document</h1>
    <div class="page-actions">
        <a href="<?php echo base_url('admin/documents'); ?>" class="btn btn-outline">
            <i class='bx bx-arrow-back'></i> Back to Documents
        </a>
    </div>
</div>

<div class="data-container container-md p-4">
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-error">
            <?php echo $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>

    <form action="<?php echo base_url('admin/documents/save'); ?>" method="POST" enctype="multipart/form-data" novalidate>
        <input type="hidden" name="id" value="<?php echo $document->id; ?>">
        
        <div class="form-group">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="<?php echo $document->title; ?>" required>
        </div>
        <div class="form-group">
            <label class="form-label">Category</label>
            <select name="category_id" class="form-control">
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat->id; ?>" <?php echo(isset($document->category_ids) && strpos($document->category_ids, $cat->id) !== false) ? 'selected' : ''; ?>>
                        <?php echo $cat->name; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3"><?php echo $document->description; ?></textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Replace File <span class="text-muted" style="font-weight: normal;">(Optional)</span></label>
            <input type="file" name="document" class="form-control" style="padding: 10px;">
            <small class="text-muted" style="display: block; margin-top: 8px;">
                <i class='bx bx-file'></i> Current file: <?php echo $document->filename; ?>
            </small>
        </div>
        
        <div class="mt-4 flex-gap-2">
            <button type="submit" class="btn btn-primary">Update Document</button>
            <a href="<?php echo base_url('admin/documents'); ?>" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>

<?php $this->load->view('partials/footer'); ?>
