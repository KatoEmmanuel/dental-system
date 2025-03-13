document.getElementById('dob').addEventListener('change', calculateAge);

function calculateAge() {
    const dob = new Date(document.getElementById('dob').value);
    const today = new Date();
    let age = today.getFullYear() - dob.getFullYear();
    const monthDiff = today.getMonth() - dob.getMonth();
    
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
        age--;
    }
    
    document.getElementById('age').value = age + ' years';
}

function updateOutput(selectId, outputId) {
    const select = document.getElementById(selectId);
    const output = document.getElementById(outputId);
    const selectedOptions = Array.from(select.selectedOptions).map(option => option.text);
    output.value = selectedOptions.join(', ');
}

document.getElementById('deciduous-select').addEventListener('change', function() {
    updateOutput('deciduous-select', 'deciduous-output');
});

document.getElementById('adults-select').addEventListener('change', function() {
    updateOutput('adults-select', 'adults-output');
}); 