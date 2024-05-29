<?php $this->layout('layouts::artifact_form', ['title' => $title, 'user' => $user]) ?>

<div class="form-outline mb-4">
    <label class="form-label" for="modelName">Model name</label>
    <input type="text" name="modelName" id="modelName" class="form-control" required />
</div>
<div class="form-outline mb-4">
    <label class="form-label" for="peripheralTypeId">Peripheral type</label>
    <select id="peripheralTypeId" name="peripheralTypeId" class="form-select" aria-label="Select the peripheral type" required>
        <option value="" hidden selected>Select the peripheral type</option>
    </select>
</div>
<input type='hidden' name='category' value='Peripheral'>


<?php $this->push('scripts_inner') ?>
<script>
    let urlPeripheralType = "/api/generic/components?category=PeripheralType";
    loadSelect(urlPeripheralType, "#peripheralTypeId");
</script>
<?php $this->end() ?>