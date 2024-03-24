<?php $this->layout('layouts::artifact_form', ['title' => $title, 'user' => $user]) ?>

<div class="form-outline mb-4">
    <label class="form-label" for="title">Title</label>
    <input type="text" name="title" id="title" class="form-control" required />
</div>
<div class="form-outline mb-4">
    <label class="form-label" for="osId">Operative System</label>
    <select id="osId" name="osId" class="form-select" aria-label="Select the operative system">
        <option value="" hidden selected>Select the operative system</option>
    </select>
</div>
<div class="form-outline mb-4">
    <label class="form-label" for="softwareTypeId">Software type</label>
    <select id="softwareTypeId" name="softwareTypeId" class="form-select" aria-label="Select the software type" required>
        <option value="" hidden selected>Select the software type</option>
    </select>
</div>
<div class="form-outline mb-4">
    <label class="form-label" for="supportTypeId">Support type</label>
    <select id="supportTypeId" name="supportTypeId" class="form-select" aria-label="Select a support type" required>
        <option value="" hidden selected>Select a support type</option>
    </select>
</div>
<input type='hidden' name='category' value='Software'>

<?php $this->push('scripts_inner') ?>
<script>
    let urlOs = "/api/generic/components?category=Os";
    loadSelect(urlOs, "#osId");
    let urlSoftwareType = "/api/generic/components?category=SoftwareType";
    loadSelect(urlSoftwareType, "#softwareTypeId");
    let urlSupportType = "/api/generic/components?category=SupportType";
    loadSelect(urlSupportType, "#supportTypeId");
</script>
<?php $this->end(); ?>