<?php $this->layout('layouts::artifact_form', ['title' => $title, 'user' => $user]) ?>

<div class="form-outline mb-4">
    <label class="form-label" for="ModelName">Nome modello</label>
    <input type="text" name="ModelName" id="ModelName" class="form-control" required />
</div>
<div class="form-outline mb-4">
    <label class="form-label" for="HddSize">Dimensioni hard disk</label>
    <input type="text" name="HddSize" id="HddSize" class="form-control" />
</div>
<div class="form-outline mb-4">
    <label class="form-label" for="Year">Anno</label>
    <input type="number" min="1500" max="2500" name="Year" id="Year" class="form-control" required />
</div>
<div class="form-outline mb-4">
    <label class="form-label" for="CpuID">Cpu</label>
    <select id="CpuID" name="CpuID" class="form-select" aria-label="Seleziona una cpu" required>
        <option value="" hidden selected>Seleziona la cpu</option>
    </select>
</div>
<div class="form-outline mb-4">
    <label class="form-label" for="RamID">Ram</label>
    <select id="RamID" name="RamID" class="form-select" aria-label="Seleziona una ram" required>
        <option value="" hidden selected>Seleziona la ram</option>
    </select>
</div>
<div class="form-outline mb-4">
    <label class="form-label" for="OsID">Sistema operativo</label>
    <select id="OsID" name="OsID" class="form-select" aria-label="Seleziona un sistema operativo">
        <option value="" hidden selected>Seleziona il sistema operativo</option>
        <option value="">Nessun sistema operativo</option>
    </select>
</div>
<input type='hidden' name='category' value='Computer'>

<?php $this->push('scripts_inner') ?>
<script>
    let urlCpu = "/api/generic/components?category=Cpu";
    loadSelect(urlCpu, "#CpuID");
    let urlRam = "/api/generic/components?category=Ram";
    loadSelect(urlRam, "#RamID");
    let urlOs = "/api/generic/components?category=Os";
    loadSelect(urlOs, "#OsID");
</script>
<?php $this->end() ?>