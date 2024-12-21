<?php
function district($selectedDistrict = null)
{
    $districts = [
        "Bhaktapur",
        "Chitwan",
        "Dhading",
        "Dolakha",
        "Kathmandu",
        "Kavrepalanchok",
        "Lalitpur",
        "Makwanpur",
        "Nuwakot",
        "Ramechhap",
        "Rasuwa",
        "Sindhuli",
        "Sindhupalchok"
    ];
    ?>
    <select id="district" name="district" data-selected="<?= htmlspecialchars($selectedDistrict) ?>">
        <option value="default">-- Select District --</option>
        <?php foreach ($districts as $district): ?>
            <option value="<?= $district ?>" <?= $district === $selectedDistrict ? 'selected' : '' ?>>
                <?= $district ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php
}

function regionNo($selectedRegionNo = null)
{
    ?>
    <select id="regionNo" name="regionNo" data-selected="<?= htmlspecialchars($selectedRegionNo) ?>">
        <option value="default">-- Select Region --</option>
    </select>
    <?php
}
?>