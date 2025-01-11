
document.addEventListener('DOMContentLoaded', function () {
    setTimeout(() => {
        const districtSelect = document.getElementById('district');
        const regionNoSelect = document.getElementById('regionNo');
    console.log(districtSelect);
    
        // Pre-selected values
        const initialDistrict = districtSelect.getAttribute('data-selected');
        const initialRegionNo = regionNoSelect.getAttribute('data-selected');
    
        // Load regions if an initial district is selected
        if (initialDistrict && initialDistrict !== "default") {
            districtSelect.value = initialDistrict;
            loadRegions(initialDistrict, initialRegionNo);
        }
    
        // Handle district change
        districtSelect.addEventListener('change', function () {
            const district = this.value;
            if (district && district !== "default") {
                loadRegions(district);
            } else {
                regionNoSelect.innerHTML = '<option value="default">-- Select Constituency --</option>';
            }
        });
    
        function loadRegions(district, preSelectedRegionNo = null) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', '../php_for_ajax/fetch_region.php?district=' + encodeURIComponent(district), true);
    
            xhr.onload = function () {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    regionNoSelect.innerHTML = '<option value="default">-- Select Constituency --</option>';
                    if (response.region_count) {
                        for (let i = 1; i <= response.region_count; i++) {
                            const option = document.createElement('option');
                            option.value = i;
                            option.textContent = i;
                            if (preSelectedRegionNo && preSelectedRegionNo == i) {
                                option.selected = true;
                            }
                            regionNoSelect.appendChild(option);
                        }
                    } else {
                        regionNoSelect.innerHTML = '<option value="default">-- No Regions Available --</option>';
                    }
                }
            };
    
            xhr.send();
        }
    
    }, 300);
   });
