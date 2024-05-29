<?php $this->layout('layouts::artifact_form', ['title' => $title, 'user' => $user]) ?>

<div class="form-outline mb-4">
    <label class="form-label" for="modelName">Model name</label>
    <input type="text" name="modelName" id="modelName" class="form-control" required />
</div>
<div class="form-outline mb-4">
    <label class="form-label" for="hddSize">Hard disk size</label>
    <input type="text" name="hddSize" id="hddSize" class="form-control" />
</div>
<div class="form-outline mb-4">
    <label class="form-label" for="year">Year</label>
    <input type="number" min="1500" max="2500" name="year" id="year" class="form-control" required />
</div>
<div class="form-outline mb-4">
    <label class="form-label" for="cpuId">Cpu</label>
    <select id="cpuId" name="cpuId" class="form-select" aria-label="Select the cpu" required>
        <option value="" hidden selected>Select the CPU</option>
    </select>
</div>
<div class="form-outline mb-4">
    <label class="form-label" for="ramId">Ram</label>
    <select id="ramId" name="ramId" class="form-select" aria-label="Select the RAM" required>
        <option value="" hidden selected>Select the RAM</option>
    </select>
</div>
<div class="form-outline mb-4">
    <label class="form-label" for="osId">Operative System</label>
    <select id="osId" name="osId" class="form-select" aria-label="Select the operative system">
        <option value="" hidden selected>Select the operative system</option>
        <option value="">No operative system</option>
    </select>
</div>
<input type='hidden' name='category' value='Computer'>

<?php $this->push('scripts_inner') ?>
<script>
    let urlCpu = "/api/generic/components?category=Cpu";
    loadSelect(urlCpu, "#cpuId");
    let urlRam = "/api/generic/components?category=Ram";
    loadSelect(urlRam, "#ramId");
    let urlOs = "/api/generic/components?category=Os";
    loadSelect(urlOs, "#osId");
</script>
<?php $this->end() ?>