<?php $this->layout('layouts::artifact_form', ['title' => $title, 'user' => $user]) ?>

<div class="form-outline mb-4">
    <label class="form-label" for="ModelName">Nome modello</label>
    <input type="text" name="ModelName" id="ModelName" class="form-control" required />
</div>
<div class="form-outline mb-4">
    <label class="form-label" for="PeripheralTypeID">Tipo di periferica</label>
    <select id="PeripheralTypeID" name="PeripheralTypeID" class="form-select" aria-label="Seleziona un tipo di periferica" required>
        <option value="" hidden selected>Seleziona un tipo di periferica</option>
    </select>
</div>
<input type='hidden' name='category' value='Peripheral'>


<?php $this->push('scripts_inner') ?>
<script>
    let urlPeripheralType = "/api/generic/components?category=PeripheralType";
    loadSelect(urlPeripheralType, "#PeripheralTypeID");
</script>
<?php $this->end() ?>